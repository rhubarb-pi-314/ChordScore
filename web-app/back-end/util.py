import json
import logging
import mysql.connector as mariadb
import django.db as django


""" establish connection with autograder database """
def db_conn():
    with open("db-config.json", "r") as fr:
        config = json.load(fr)
    mariadb_conn = mariadb.connect(
        user=config['db-auth']['user'],
        password=config['db-auth']['password'],
        database='autograder'
    )
    return mariadb_conn


""" return list as comma-separated string of items """
def get_list_as_str(ls):
    ls_str = str(ls[0])
    for i in range(1,len(ls)):
        ls_str += ',{}'.format(ls[i])
    return ls_str


""" returns...
        1) dict as comma-separated conditional statements
            of the format [variable(key)]=%s
        2) dict keys as a list (to establish order in which keys were used in conditional statement) """
def get_conditions_dict_as_str(conditions):
    keys = conditions.keys()
    strings = ['{}=%s'.format(k) for k in keys]
    condition_stmnt = '{} '.format(strings[0])
    for s in range(1, len(strings)):
        condition_stmnt += 'AND {} '.format(s)
    return condition_stmnt, keys


""" logs error message (attributed to classname) """
def log_error(message, classname=None):
    with open("file-system-config.json","r") as fr:
        config = json.load(fr)
    logging.basicConfig(
        filename=config["logging"]["backend-error-logs-fpath"],
        level=logging.ERROR
    )
    error_msg = 'In {} class - '.format(classname) if classname is not None else ''
    logging.error("{}{}".format(error_msg, message))

""" logs informative message (attributed to classname) """
def log_info(message, classname=None):
    with open("file-system-config.json","r") as fr:
        config = json.load(fr)
    logging.basicConfig(
        filename=config["logging"]["backend-debug-logs-fpath"],
        level=logging.INFO
    )
    loc_msg = 'In {} class - '.format(classname) if classname is not None else ''
    logging.info("{}{}".format(loc_msg, message))


""" inserts [value_tuple] values into [column_list] columns into tablename
    raises DatabaseError if an error occurs
    error triggers WRITE to log_error.log file """
def insert(tablename, column_list, value_tuple, classname=None):
    conn = db_conn()
    cursor = conn.cursor()
    query = "INSERT INTO {} ({}) VALUES ({})".format(
        tablename,
        get_list_as_str(column_list),
        get_list_as_str(["%s" for _ in value_tuple])
    )
    try:
        cursor.execute(query, value_tuple)
    except:
        log_error('Failed to insert data. Failed query was "{}" and values were'.format(query, value_tuple),classname)
        cursor.close()
        raise django.DatabaseError

    try:
        auto_id = conn.insert_id()
    except:
        auto_id = None
        cursor.close()
    cursor.close()
    return  auto_id

""" updates row in [tablename] table; columns in [column_list] updated wherever
        the conditions in the [value_match_dict] (column, value) pairs hold true """
def update(tablename, column_value_dict, value_match_dict, classname=None):
    conn = db_conn()
    cursor = conn.cursor(prepared=True)
    col_keys = column_value_dict.keys()
    cols = get_list_as_str(["{} = %s".format(col_keys[k]) for k in col_keys])
    vals = [column_value_dict[k] for k in col_keys]
    condition, cond_keys = get_conditions_dict_as_str(value_match_dict)
    cond_vals = [value_match_dict[k] for k in cond_keys]
    query = "UPDATE {} SET {} WHERE {}".format(tablename,cols,condition)
    try:
        cursor.execute(query, vals + cond_vals)
    except:
        log_error('Failed to update table {}. Failed query was "{}" and values were'.
                  format(tablename, query, vals + cond_vals),
                  classname)
        cursor.close()
        raise django.DatabaseError
    cursor.close()


""" selects values from [column_list] columns of [tablename] table upon condition
        of [value_match_dict] (column, value) pair
    raises DatabaseError if an error occurs
    error triggers WRITE to log_error.log file """
def select(tablename, column_list, value_match_dict, classname=None):
    conn = db_conn()
    cursor = conn.cursor()
    condition, keys = get_conditions_dict_as_str(value_match_dict)
    query = "SELECT {} FROM {} WHERE {}".format( get_list_as_str(column_list), tablename, condition)
    try:
        cursor.execute(query, [value_match_dict[k] for k in keys])
        results = []
        for result in cursor.fetchall():
            results.append(result)
    except:
        log_error('Failed to select data. Failed query was "{}" and values were'.
                        format(query, [value_match_dict[k] for k in keys]),
                    classname)
        cursor.close()
        raise django.DatabaseError
    cursor.close()
    return results


""" deletes values from [tablename] table where conditions from [value_match_dict] apply """
def delete(tablename, value_match_dict, classname=None):
    conn = db_conn()
    cursor = conn.cursor()
    condition, keys = get_conditions_dict_as_str(value_match_dict)
    query = "DELETE FROM {} WHERE {}".format(tablename, condition)
    try:
        cursor.execute(query, [value_match_dict[k] for k in keys])
        nbr_rows_deleted = []
        for r in cursor.fetchall():
            nbr_rows_deleted.append(r)
    except:
        log_error('Failed to delete data. Failed query was "{}" and values were'.
                  format(query, [value_match_dict[k] for k in keys]),
                  classname)
        cursor.close()
        raise django.DatabaseError
    cursor.close()
    return nbr_rows_deleted


def send_email(email, message):
    print("STUB: sending message to {}".format(email))


class FileManager:

    def __init__(self):
        pass


class Image:

    def __init__(self):
        pass

