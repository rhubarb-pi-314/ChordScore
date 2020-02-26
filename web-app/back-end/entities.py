import json, os
import util, datetime, random

from django.db import Error, DatabaseError, DataError


class Account:

    def __init__(self, account_type, email, fname, lname, pwd, update_db=False):
        self.account_id         = None
        self.account_type       = account_type
        self.email              = email
        self.fname, self.lname  = fname, lname
        self.pwd                = pwd
        if update_db:
            self.account_id = util.insert(
                'Account',
                ('account_type','email','fname','lname','pwd'),
                (self.account_type,self.email,self.fname,self.lname,self.pwd),
                classname='Account'
            )

    @staticmethod
    def get_account(account_id):
        results = util.select('Account', ('account_id'),{'account_id':account_id},classname='Account')
        return results[0][0]

    @staticmethod
    ### remove entries with account_id from the following tables
    def remove(account_id):
        account = Account.get_account(account_id)
        util.delete('Pwd_reset',{'account_id':account_id},classname=Account.__name__)
        if account.account_type == 'instructor':
            db_read = util.select('Class',('class_id'),{'account_id':account_id},classname=Account.__name__)
            class_ids = [r[0] for r in db_read]
            for cid in class_ids:
                student_account_ids = ClassList.get_student_account_ids(cid)
                for sid in student_account_ids:
                    Assignment.remove_assignments_for_student(sid)
                ClassList.remove_class(cid)
                Class.remove(cid)
                AssignmentDefinition.remove_all_for_instructor(account_id)
        else:
            ClassList.remove_student_from_class(account_id)
            assignments = Assignment.get_all_assignments(account_id)
            for a in assignments:
                a.remove_assignments_for_student(account_id)

        util.delete('Account',{'account_id':account_id},classname=Account.__name__)

class PasswordManager:

    @staticmethod
    def set_pwd(account_id, pwd):
        reset_key = ''
        for i in range(15):
            reset_key += hex(random.randint(15))[2:]
        util.insert('Pwd_reset',('account_id','pwd_reset_key'),(account_id,reset_key),classname=PasswordManager.__name__)
        util.insert('Account',('pwd'),(pwd),classname=PasswordManager.__name__)

    @staticmethod
    def get_account_id(email, pwd):
        results = util.select('Account',('account_id'), {'email':email,'pwd':pwd},classname=PasswordManager.__name__)
        return results[0][0]

    @staticmethod
    def send_change_request(email):
        util.send_email(email, 'message')

    @staticmethod
    def verify_change_request(account_id, pwd_reset_key, old_pwd, new_pwd):
        valid_request = False
        stored_pwd_reset_key = util.select('PwdReset',('pwd_reset_key'),{'account_id':account_id},
                                           classname=PasswordManager.__name__)[0]
        stored_old_pwd = util.select('PwdReset',('pwd'),{'account_id':account_id},
                                     classname=PasswordManager.__name__)[0]
        if stored_pwd_reset_key == pwd_reset_key and stored_old_pwd == old_pwd:
            PasswordManager.set_pwd(account_id, new_pwd)
            valid_request = True
        return valid_request


class Class:

    def __init__(self, account_id, class_key, title, semester, start_date, end_date,
                 update_db=False, generate_instructor_key=False):

        self.class_id       = None
        self.account_id     = account_id
        self.class_key      = class_key
        self.title          = title
        self.semester       = semester
        self.start_date     = start_date
        self.end_date       = end_date

        if update_db:
            self.class_id = util.insert(
                'Class',
                ('account_id','class_key','title','semester','start_date', 'end_date'),
                (self.account_id, self.class_key, self.title, self.semester, self.start_date, self.end_date),
                classname=Class.__name__
            )

        if generate_instructor_key:
            #TODO make sure this unique
            key = ''
            for i in range(15):
                key += hex(random.randint(15))[2:]
            util.insert('Instructor_key',('account_id','instruction_key'),(account_id,key),classname=Class.__name__)


    @staticmethod
    def get_class(class_id: int):
        """ :param   class_id: int
            :returns Class object associated with class_id"""

        result = util.select(
            'Class',
            ('account_id','class_key','title','semester','start_date','end_date'),
            {'class_id':class_id},
            classname='Class'
        )[0]
        account_id, class_key, title, semester, start_date, end_date = result
        class_obj = Class(account_id,class_key,title,semester,start_date,end_date)
        class_obj.class_id = class_id
        return class_obj

    @staticmethod
    def get_classes(account_id):
        """ @:parameter account_id of instructor or student account
            @:returns   class_ids of Class objects associated with account_id """

        account = Account.get_account(account_id)
        if account.account_type == 'instructor':
            db_read = util.select('Class', ('class_id'), {'account_id':account_id},classname=Class.__name__)
            class_ids = [int(r[0]) for r in db_read]
        else:
            db_read = util.select('ClassList',('class_id'),{'account_id':account_id},classname=Class.__name__)
            class_ids = [int(r[0]) for r in db_read]
        return class_ids

    @staticmethod
    def remove(class_id):
        ClassList.remove_class(class_id)
        c = Class.get_class(class_id)
        util.delete('Instructor_key',{'account_id':c.account_id},classname=Class.__name__)
        util.delete('Class_assignments',{'class_id':class_id},classname=Class.__name__)
        util.delete('Class',{'class_id':class_id},classname=Class.__name__)


class ClassList:

    @staticmethod
    def add_student(class_id, account_id):
        util.insert('Class_list',('account_id','class_id'),(account_id,class_id),classname=ClassList.__name__)

    @staticmethod
    def get_student_account_ids(class_id):
        db_read = util.select('Class_list',('account_id'),{'class_id':class_id},classname=ClassList.__name__)
        return [int(r[0]) for r in db_read]

    @staticmethod
    def remove_class(class_id):
        util.delete('Class_list',{'class_id':class_id},classname=ClassList.__name__)

    @staticmethod
    def remove_student_from_class(account_id):
        util.delete('Classs_list',{'account_id':account_id},classname=ClassList.__name__)


class AssignmentDefinition:

    def __init__(self, account_id, title, instructions, class_id, update_db=False):
        self.assignment_def_id  = None
        self.account_id         = account_id
        self.title              = title
        self.instructions       = instructions
        self.class_id           = class_id
        self.def_file_path      = None

        if update_db:
            self.assignment_def_id = util.insert(
                'Assignment_definition',
                ('account_id','title','instructions'),
                (self.account_id,self.title,self.instructions),
                classname='AssignmentDefinition'
            )
            util.insert(
                'Class_assignments',
                ('assignment_def_id','class_id'), (self.assignment_def_id,self.class_id),
                classname='AssignmentDefinition'
            )


    @staticmethod
    def set_answer_key(assignment_def_id,  answer_key_fpath):
        db_read = util.select(
            'Answer_key',
            ('answer_key_id'),
            {'assignment_def_id':assignment_def_id},
            classname='AssignmentDefinition'
        )
        if len(db_read) > 0:    # case that Answer Key already exists for Assignment Def
            answer_key_id = db_read[0][0]
            util.update(
                'Answer_key',
                {'answer_key_id':answer_key_id},
                {'answer_key_file_path':answer_key_fpath},
                classname='AssignmentDefinition'
            )
        else:
            util.insert(
                'Answer_key',
                ('assignment_def_id','answer_key_file_path'),
                (assignment_def_id,answer_key_fpath),
                classname=AssignmentDefinition.__name__
            )

    @staticmethod
    def get_assignment_definition(assignment_def_id):
        db_read = util.select(
            'Assignment_definition',
            ('account_id','title','instructions'), {'assignment_def_id':assignment_def_id},
            classname=AssignmentDefinition.__name__
        )
        account_id, title, instructions = db_read[0]
        db_read = util.select(
            'Class_assignments',
            ('class_id'), {'assignment_def_id':assignment_def_id},
            classname=AssignmentDefinition.__name__
        )
        class_id = db_read[0][0]
        ad = AssignmentDefinition(account_id,title,instructions,class_id)
        ad.assignment_def_id = assignment_def_id

    @staticmethod
    def get_all_assignment_definitions(class_id):
        db_read = util.select(
            'Class_assignments',
            ('assignment_def_id'), {'class_id':class_id},
            classname=AssignmentDefinition.__name__
        )
        adefs = []
        for row in db_read:
            adef_id = row[0]
            adefs.append(AssignmentDefinition.get_assignment_definition(adef_id))
        return adefs

    @staticmethod
    def load_from_file(assignment_def_id):
        #TODO: implement
        pass

    @staticmethod
    def remove(assignment_def_id):
        AnswerKey.remove(assignment_def_id)
        util.delete('Class_assignments',{'assignment_def_id':assignment_def_id},classname='AssignmentDefinition')
        util.delete('Assignment_definition',{'assignment_def_id':assignment_def_id},classname='AssignmentDefinition')

    @staticmethod
    def remove_all_for_instructor(account_id):
        db_read = util.select(
            'Assignment_definition',
            ('assignment_def_id'),{'account_id':account_id},
            classname=AssignmentDefinition.__name__
        )
        adef_ids = [int(r[0]) for r in db_read]
        for aid in adef_ids:
            AssignmentDefinition.remove(aid)

class AnswerKey:

    def __init__(self, assignment_def_id, answer_key_fpath, update_db=False):
        self.answer_key_id      = None
        self.assignment_def_id  = assignment_def_id
        self.answer_key_fpath   = answer_key_fpath
        self.fields             = []

        if update_db:
            self.answer_key_id = util.insert(
                'Answer_key',
                ('assignment_def_id','answer_key_fpath'),
                (self.assignment_def_id,self.answer_key_fpath),
                classname='AnswerKey'
            )
            self.fields = Field.get_fields(self.answer_key_id)

    @staticmethod
    def get_answer_key(answer_key_id):
        results = util.select('Answer_key',('assignment_def_id','answer_key_fpath'),{'answer_key_id':answer_key_id},
                              classname='AnswerKey')
        (assignment_def_id, answer_key_fpath) = results[0]
        answer_key = AnswerKey(assignment_def_id,answer_key_fpath)
        answer_key.fields = Field.get_fields(answer_key_id)
        return answer_key

    @staticmethod
    def remove(assignment_def_id):
        answer_key_id = util.select('Answer_key',('answer_key_id'),{'assignment_def_id':assignment_def_id})[0][0]

        fields = Field.get_fields(answer_key_id)
        for field in fields:
            field.remove(answer_key_id)
        util.delete('Answer_key',{'answer_key_id':answer_key_id})



class Field:

    def __init__(self, answer_key_id, x, y, xdim, ydim, field_type, update_db=False):
        self.field_id           = None
        self.answer_key_id      = answer_key_id
        self.x, self.y          = x, y
        self.xdim, self.ydim    = xdim, ydim
        self.field_type         = field_type

        if update_db:
            self.field_id = util.insert(
                'Field',
                ('answer_key_id','x','y','xdim','ydim','field_type'),
                (self.answer_key_id,self.x,self.y,self.xdim,self.ydim,self.field_type),
                classname=Field.__name__
            )

    @staticmethod
    def get_fields(answer_key_id):
        fields = []
        results = util.select(
            'Field',
            ('field_id','x','y','xdim','ydim','field_type'),
            {'answer_key_id':answer_key_id},
            classname=Field.__name__
        )
        for r in results:
            (field_id, x, y, xdim, ydim, field_type) = r
            field = Field(answer_key_id,x,y,xdim,ydim,field_type)
            field.field_id = field_id
            fields.append(field)
        return fields

    @staticmethod
    def remove(answer_key_id):
        util.delete('Field',{'answer_key_id': answer_key_id},classname=Field.__name__)


class Assignment:

    def __init__(self, account_id, assignment_def_id, answer_key_id,
                 open_dt, due_dt, update_db=False):
        self.assignment_id      = None
        self.account_id         = account_id
        self.assignment_def_id  = assignment_def_id
        self.answer_key_id      = answer_key_id
        self.open_dt            = open_dt
        self.due_dt             = due_dt

        self.graded             = False
        self.grade              = 0
        self.marked_up_fpath    = None

        if update_db:
            self.assignment_id = util.insert(
                'Assignment',
                ('account_id','assignment_df_id','answer_key_id','open_datetime','due_datetime','graded','grade'),
                (self.account_id,self.assignment_def_id,self.answer_key_id,self.open_dt,self.due_dt,False,0),
                classname=Assignment.__name__
            )


    @staticmethod
    def remove(assignment_id):
        SubmissionFile.remove_files(assignment_id)
        util.delete('Assignment',{'assignment_id':assignment_id},'Assignment')

    @staticmethod
    def remove_assignments(assignment_def_id):
        aids = util.select('Assignment',('assignment_id'),{'assignment_def_id':assignment_def_id},classname=Assignment.__name__)
        for aid in aids:
            SubmissionFile.remove_files(aid)
        nbr_rows_del = util.delete('Assignment',{'assignment_def_id':assignment_def_id},classname=Assignment.__name__)
        util.log_info('Deleted {} assignments where assignment_def_id={}'.format(nbr_rows_del,assignment_def_id))

    @staticmethod
    def remove_assignments_for_student(account_id):
        aids = util.select('Assignment', ('assignment_id'), {'account_id': account_id},
                           classname=Assignment.__name__)
        for aid in aids:
            SubmissionFile.remove_files(aid)
        nbr_rows_del = util.delete('Assignment',{'account_id':account_id},classname=Assignment.__name__)
        util.log_info('Deleted {} assignments where account_id={}'.format(nbr_rows_del,account_id))

    @staticmethod
    def get_assignments(assignment_def_id):
        db_read = util.select(
            'Assignments',
            ('assignment_id','account_id','answer_key_id','open_datetime','due_datetime','graded','grade','marked_up_fpath'),
            {'assignment_def_id':assignment_def_id}
        )
        assignments = []
        for r in db_read:
            (assignment_id,account_id,answer_key_id,open_datetime,due_datetime,graded,grade,marked_up_fpath) = r
            a = Assignment(account_id,assignment_def_id,answer_key_id,open_datetime,due_datetime)
            a.assignment_id = assignment_id
            assignments.append(a)
        return assignments

    @staticmethod
    def get_all_assignments(account_id):
        db_read = util.select(
            'Assignment',
            ('assignment_id','assignment_def_id','answer_key_id','open_datetime','due_datetime','graded','grade','marked_up_file_path'),
            {'account_id':account_id},
            classname=Assignment.__name__
        )
        assignments = []
        for row in db_read:
            assignment_id, assignment_def_id, answer_key_id, open_dt, due_dt, graded, grade, marked_up_file_path = row
            a = Assignment(account_id,assignment_def_id,answer_key_id,open_dt,due_dt)
            a.assignment_id = assignment_id
            a.graded, a.grade, a.marked_up_fpath = graded, grade, marked_up_file_path
            assignments.append(a)
        return assignments

    @staticmethod
    def grade(assignment_def_id, account_id):
        # CALLS ML models
        print("GRADING stub: 95 assigned as grade")
        util.update(
            'Assignment',
            {'graded':True,'grade':50},
            {'assignment_def_id':assignment_def_id,'account_id':account_id},
            classname=Assignment.__name__
        )


class SubmissionFile:

    def __init__(self, assignment_id, submission_fpath, submission_dt, update_db=False):
        self.assignment_id      = assignment_id
        self.submission_fpath   = submission_fpath
        self.submission_dt      = submission_dt

        if update_db:
            self.submission_id = util.insert(
                'Submission_files',
                ('assignment_id','submission_fpath', 'submission_datetime'),
                (self.assignment_id,self.submission_fpath,self.submission_dt),
                classname=SubmissionFile.__name__
            )

    @staticmethod
    def get_submission_files(assignment_id):
        values = []
        db_read = util.select(
            'Submission_files',
            ('submission_id','submission_fpath','submission_datetime'),
            {'assignment_id':assignment_id},
            classname=SubmissionFile.__name__
        )
        for r in db_read:
            (submission_id, submission_fpath, submission_datetime) = r
            sf = SubmissionFile(assignment_id,submission_fpath,submission_datetime)
            sf.assignment_id = assignment_id
            values.append(sf)
        return values


    @staticmethod
    def remove_files(assignment_id):
        nbr_rows_del = util.delete(
            'Submission_files',
            {'assignment_id':assignment_id},
            classname=SubmissionFile.__name__
        )
        return nbr_rows_del


