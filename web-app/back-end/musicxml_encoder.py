import os, numpy
import xml.etree.ElementTree as ET

class Encoder:

    def __init__(self, musicxml_fpath):
        self.DIM_CHORD_LOC   = 4
        self.DIM_CHORD_CLASS = 27
        self.DIM_CHANNEL     = 7
        self.NBR_CHANNELS    = 8
        self.CHORD_CLASS_ENC = [
                'whole-note', 'half-note', 'quarter-note', 'eighth-note', '16th-note',
                '32nd-note', '64th-note', 'unknown-note', 'whole-rest', 'half-rest',
                'quarter-rest', 'eighth-rest', '16th-rest', '32nd-rest', '64th-rest',
                'unknown-rest', 'augmented', 'double-augmented', 'triple-augmented',
                'g-clef', 'f-clef', 'c-clef', 'clef-pitch', 'clef-octave-shift',
                'key-signature', 'time-signature-beats', 'time-signature-unit'
        ]
        self.NOTE_ENC       = [
                'note','pitch','double-sharp','sharp','natural','flat','double-flat'
        ]

        self.NOTE            = {'C':0,'D':1,'E':2,'F':3,'G':4,'A':5,'B':6}
        self.CLEF_OCTAVE     = {'G':4, 'F':2, 'C':3}

        self.KEY_SIG_SHRP_PITCHES = {
            'G': {  1:[ 2,-1, 3, 0,-3, 1,-2],
                    2:[ 4, 1, 5, 2,-1, 3, 0]  },
            'C': {  1:[-1, 3, 0, 4, 1, 5, 2],
                    2:[ 1,-2, 2,-1, 3, 0, 4],
                    3:[ 3, 0, 4, 1,-2, 2,-1],
                    4:[-2, 2,-1, 3, 0, 4, 1],
                    5:[ 0, 4, 1, 5, 2,-1, 3]  },
            'F': {  3:[ 0, 4, 1, 5, 2,-1, 3],
                    4:[ 2,-1, 3, 0,-3, 1,-2],
                    5:[ 4, 1, 5, 2,-1, 3, 0]  }
        }
        self.KEY_SIG_FLAT_PITCHES = {
            'G': {  1:[-2, 1,-3, 0,-4,-1,-5],
                    2:[ 0, 3,-1, 2,-2, 1,-3]  },
            'C': {  1:[ 2,-2, 1,-3, 0,-4,-1],
                    2:[ 4, 0, 3,-1, 2,-2, 1],
                    3:[-1, 2,-2, 1,-3, 0,-4],
                    4:[ 1, 4, 0, 3,-1, 2,-2],
                    5:[ 3,-1, 2,-2, 1,-3, 0]  },
            'F': {  3:[ 3,-1, 2,-2, 1,-3, 0],
                    4:[-2, 1,-3, 0,-4,-1,-5],
                    5:[ 0, 3,-1, 2,-2, 1,-3]  }
        }

        self.key_tracker            = {}
        self.duration_marker        = {}  ## this is new. key 1 = part, key 2 = staff, key 3 = voice
        self.time_tracker           = {}
        self.clef_tracker           = {}
        self.beat_divisions         = {}
        self.voice_encoder          = {}
        self.voice_progress_tracker = {}

        self.src_fpath = musicxml_fpath
        self.tree = ET.parse(self.src_fpath)
        self.root = self.tree.getroot()
        self.chord_array = self.__get_chord_array__()
        #self.chord_np_array = numpy.array([numpy.array(row) for row in self.chord_array])
        self.page_starts = []

    def __get_chord_array__(self):
        #chords = self.__get_chords__(1)
        return None

    def __get_chords__(self,measure_number):
        parts = self.tree.findall('.//part')
        chords = []

        for p in parts:

            measures = self.tree.findall('.//part[@id="{}"]/measure[@number="{}"]'.format(p.get('id'),measure_number))
            print("part {}".format(p.get("id")))

            # iterate through measures
            for m in measures:
                print("measure {}".format(measure_number))

                measure_nbr, part_nbr = int(measure_number), int(p.get('id')[1:])
                notes = [e for e in m if e.tag == 'note']
                staves = []
                for n in notes:
                    staff = [int(e.text) for e in n if e.tag == 'staff']
                    staff = 1 if len(staff) == 0 else staff[0]
                    staves.append(staff)
                nbr_staves = max(staves)

                key_signature_change  = []     # list of staff numbers affected by key signature change
                time_signature_change = []     # list of staff numbers affected by time signature change
                clef_change           = []     # list of staff numbers affected by clef change

                chords_by_staff = {}    # key = staff, value = [chord, chord, ect.]

                # iterate through measure elements
                for elem in m:
                    print('elem {}'.format(elem.tag))

                    ## SECTION 1 - meta info: from attributes look for and store key, time, and clef ##
                    if elem.tag == 'attributes':
                        attrib = elem
                        divisions = [e for e in attrib if e.tag == 'divisions']
                        divisions = divisions[0] if len(divisions) > 0 else None
                        key = [e for e in attrib if e.tag == 'key']
                        key = key[0] if len(key) > 0 else None
                        time = [e for e in attrib if e.tag == 'time']
                        time = time[0] if len(time) > 0 else None
                        clefs_by_staff = [e for e in attrib if e.tag == 'clef']

                        if divisions is not None:     # store beat divisions by part
                            div = int(divisions.text)
                            self.beat_divisions[part_nbr] = div

                        if key is not None:           # store key signature change by part
                            fifths = [e for e in key if e.tag == 'fifths'][0].text
                            self.key_tracker[part_nbr] = fifths
                            if int(fifths) != 0:    
                                if 'number' in key.keys():
                                    staff = int(key.get('number'))
                                    key_signature_change.append(staff)
                                else:
                                    key_signature_change = [v+1 for v in range(nbr_staves)]

                        if time is not None:          # store time signature change by part
                            beats     = [e for e in time if e.tag == 'beats'][0]
                            beat_type = [e for e in time if e.tag == 'beat-type'][0]
                            self.time_tracker[part_nbr] = (beats.text, beat_type.text)
                            if 'number' in time.keys():
                                staff = int(time.get('number'))
                                time_signature_change.append(staff)
                            else:
                                time_signature_change = [v+1 for v in range(nbr_staves)]

                        if len(clefs_by_staff) > 0:   # store clef changes by part and by staff
                            for clef in clefs_by_staff:      # get the staff number
                                if 'number' in clef.keys():
                                    staff_nbr = int(clef.get('number'))
                                else:
                                    staff_nbr = 1
                                                        # get clef informatino
                                clef_type = [e for e in clef if e.tag == 'sign'][0].text
                                clef_line = [e for e in clef if e.tag == 'line'][0].text
                                octave_shift = [e for e in clef if e.tag == 'clef-octave-change']
                                if len(octave_shift) > 0:
                                    octave_shift = octave_shift[0].text
                                else:
                                    octave_shift = 0
                                                            # store clef change by part & staff
                                if part_nbr not in self.clef_tracker.keys():
                                    self.clef_tracker[part_nbr] = {}
                                self.clef_tracker[part_nbr][staff_nbr] = (clef_type, clef_line, octave_shift)
                                clef_change.append(staff_nbr)

                    ## SECTION 2 - line & page info ##
                    elif elem.tag == 'print':
                        printinfo = elem 

                        # new-page="yes"   -> mark page break, re-articulate current clef & key signature
                        if 'new-page' in printinfo.keys() and printinfo.get('new-page') == 'yes':
                            clef_change          = [v+1 for v in range(nbr_staves)]
                            key_signature_change = [v+1 for v in range(nbr_staves)] \
                                                        if int(self.key_tracker[part_nbr]) != 0 else []
                            self.page_starts.append(measure_nbr)

                        # new-system="yes" -> re-articulate current clef & key signature
                        if 'new-system' in printinfo.keys() and printinfo.get('new-system') == 'yes':
                            clef_change          = [v+1 for v in range(nbr_staves)]
                            key_signature_change = [v+1 for v in range(nbr_staves)] \
                                                        if int(self.key_tracker[part_nbr]) != 0 else []

                    ## SECTION 3 - assemble series of chords for this measure ##
                        #             order of chords is...
                        #               1st by part_nbr
                        #               2nd by staff_nbr
                        #               3rd by measure_nbr
                        #             order of "chord" types is...
                        #               > clef           - if first measure, new system, or clef change for part and staff
                        #               > key-signature  - if first measure, new system, or key-signature change
                        #               > time-signature - if first measure, new system, or time-signature change
                        #               > note
                    # get list of all chord objects and separate by staff            
                    elif elem.tag == 'note':

                        note = elem
                        elem_names = [e.tag for e in note]

                        ## note positional information - staff nbr and voice nbr
                        
                        # staff number
                        staff_nbr  = 1 if 'staff' not in elem_names else int([e for e in note if e.tag=='staff'][0].text)

                        # voice number
                        if 'voice' not in elem_names:
                            voice_nbr = 1
                        else:
                            voice = int([e for e in note if e.tag == 'voice'][0].text)
                        if part_nbr not in self.voice_encoder.keys():
                            self.voice_encoder[part_nbr] = {voice:1}
                            voice_nbr = 1
                        else:
                            if voice in self.voice_encoder[part_nbr].keys():
                                voice_nbr = self.voice_encoder[part_nbr][voice]
                            else:
                                max_voice_enc = max(self.voice_encoder[part_nbr])
                                self.voice_encoder[part_nbr][voice] = max_voice_enc + 1
                                voice_nbr = max_voice_enc + 1

                        ## determine object class name
                        is_note    = 'rest' not in elem_names
                        type_given = 'type' in elem_names

                        ## create new chord
                        new_chord  = 'chord' not in elem_names
                        if new_chord:
                            chord = None

                            debugging = False
                            if debugging:
                                print('new chord and these are flags...')
                                print('   clef_change={}'.format(clef_change))
                                print('   key_signature_change={}'.format(key_signature_change))
                                print('   time_signature_change={}'.format(time_signature_change))

                            if staff_nbr not in chords_by_staff.keys():
                                chords_by_staff[staff_nbr] = []

                            # 1) any clef elements
                            if staff_nbr in clef_change:
                                clef_type, clef_line, octave_shift = self.clef_tracker[part_nbr][staff_nbr]
                                chord = self.__get_clef_chord__(measure_nbr, part_nbr, staff_nbr, clef_type, clef_line, octave_shift)
                                chords_by_staff[staff_nbr].append(chord)
                                clef_change.remove(staff_nbr)
                               

                            # 2) any key signature elements
                            if staff_nbr in key_signature_change:
                                fifths = self.key_tracker[part_nbr]
                                chord = self.__get_key_signature_chord__(measure_nbr, part_nbr, staff_nbr, fifths)
                                chords_by_staff[staff_nbr].append(chord)
                                key_signature_change.remove(staff_nbr)

                            # 3) any time signature elements
                            if staff_nbr in time_signature_change:
                                beat, beat_type = self.time_tracker[part_nbr]
                                chord = self.__get_time_signature_chord__(measure_nbr, part_nbr, staff_nbr, beat, beat_type)
                                chords_by_staff[staff_nbr].append(chord)
                                time_signature_change.remove(staff_nbr)

                            # add as note
                            if is_note:
                                note_type = None if not type_given else [e for e in note if e.tag == 'type'][0].text
                                dots      = len([e for e in note if e.tag == 'dot'])
                                duration  = 0 if note_type is not None else int([e for e in note if e.tag == 'duration'][0].text)
                                chord     = self.__get_note_chord__(
                                    measure_nbr, part_nbr, staff_nbr, voice_nbr, note_type=note_type, dots=dots, duration=duration
                                )
                                pitch       = [e for e in note if e.tag == 'pitch'][0]
                                note_letter = [e for e in pitch if e.tag == 'step'][0].text
                                octave      = [e for e in pitch if e.tag == 'octave'][0].text
                                accidental  = None if 'accidental' not in elem_names else [e for e in note if e.tag == 'accidental'][0].text
                                self.__add_note_pitch__(chord, part_nbr, staff_nbr, note_letter, octave, accidental=accidental)
                                chords_by_staff[staff_nbr].append(chord)

                            # add as rest
                            else:
                                rest_type = None if not type_given else [e for e in note if e.tag == 'type'][0].text
                                dots      = len([e for e in note if e.tag == 'dot'])
                                duration  = 0 if rest_type is not None else int([e for e in note if e.tag == 'duration'][0].text)
                                chord     = self.__get_rest_chord__(
                                    measure_nbr, part_nbr, staff_nbr, voice_nbr, rest_type=rest_type, dots=dots, duration=duration
                                )
                                chords_by_staff[staff_nbr].append(chord)

                        ## add notes to existing chord
                        else:
                            chord = chords_by_staff[staff_nbr][len(chords_by_staff[staff_nbr])-1]
                            pitch       = [e for e in note if e.tag == 'pitch'][0]
                            note_letter = [e for e in pitch if e.tag == 'step'][0].text
                            octave      = [e for e in pitch if e.tag == 'octave'][0].text
                            accidental  = None if 'accidental' not in elem_names else [e for e in note if e.tag == 'accidental'][0].text
                            self.__add_note_pitch__(chord, part_nbr, staff_nbr, note_letter, octave, accidental=accidental)

                for stf_nbr in chords_by_staff.keys():
                    for ch in chords_by_staff[stf_nbr]:
                        chords.append(ch)

        return chords
      
      
    """ UTILITY FUNCTIONS """

    ### allocates empty chord array
    def __new_chord__(self):
        return [0]*(self.DIM_CHORD_LOC + self.DIM_CHORD_CLASS + self.DIM_CHANNEL*self.NBR_CHANNELS)

    ### generates and returns a key-signature "chord"
    def __get_key_signature_chord__(self, measure_nbr, part_nbr, staff_nbr, fifths):
        fifths = int(fifths)
        chord = self.__new_chord__()
        chord[0], chord[1], chord[2], chord[3] = part_nbr, staff_nbr, measure_nbr, 1
        chord[self.DIM_CHORD_LOC + self.CHORD_CLASS_ENC.index('key-signature')] = 1
        clef_type, clef_line, octave_shift = self.clef_tracker[part_nbr][staff_nbr]
        if fifths > 0:
            accidentals = self.KEY_SIG_SHRP_PITCHES[clef_type][int(clef_line)][:fifths]
            for i, pitch in enumerate(accidentals):
                channel_i = self.DIM_CHORD_LOC + self.DIM_CHORD_CLASS + i*self.DIM_CHANNEL
                chord[channel_i + self.NOTE_ENC.index('pitch')] = pitch
                chord[channel_i + self.NOTE_ENC.index('sharp')] = 1
        elif fifths < 0:
            accidentals = self.KEY_SIG_FLAT_PITCHES[clef_type][int(clef_line)][:abs(fifths)]
            for i, pitch in enumerate(accidentals):
                channel_i = self.DIM_CHORD_LOC + self.DIM_CHORD_CLASS + i*self.DIM_CHANNEL
                chord[channel_i + self.NOTE_ENC.index('pitch')] = pitch
                chord[channel_i + self.NOTE_ENC.index('flat') ] = 1
        return chord

    ### generates and returns a time-signature "chord"
    def __get_time_signature_chord__(self, measure_nbr, part_nbr, staff_nbr, beat, beat_type):
        beat, beat_type = int(beat), int(beat_type)
        chord = self.__new_chord__()
        chord[0], chord[1], chord[2], chord[3] = part_nbr, staff_nbr, measure_nbr, 1
        chord[self.DIM_CHORD_LOC + self.CHORD_CLASS_ENC.index('time-signature-beats')] = beat
        chord[self.DIM_CHORD_LOC + self.CHORD_CLASS_ENC.index('time-signature-unit') ] = beat_type
        return chord

    ### generates and returns a clef "chord"
    def __get_clef_chord__(self, measure_nbr, part_nbr, staff_nbr, clef_type, clef_line, octave_shift):
        clef_type = clef_type.upper()
        clef_line, octave_shift = int(clef_line), int(octave_shift)
        chord = self.__new_chord__()
        chord[0], chord[1], chord[2], chord[3] = part_nbr, staff_nbr, measure_nbr, 1
        chord[self.DIM_CHORD_LOC + self.CHORD_CLASS_ENC.index('{}-clef'.format(clef_type.lower()))] = 1
        chord[self.DIM_CHORD_LOC + self.CHORD_CLASS_ENC.index('clef-pitch')] = (int(clef_line) - 3) * 2
        chord[self.DIM_CHORD_LOC + self.CHORD_CLASS_ENC.index('clef-octave-shift')] = int(octave_shift)
        return chord

    ### generates a rest "chord"
    def __get_rest_chord__(self, measure_nbr, part_nbr, staff_nbr, voice_nbr, rest_type=None, dots=0, duration=0):
        chord = self.__new_chord__()                                                         
        chord[0], chord[1], chord[2], chord[3] = part_nbr, staff_nbr, measure_nbr, voice_nbr 
        if rest_type is None:
            rest_type, augmentation = self.__get_note_type__(part_nbr, duration, rest=True)
        else:
            rest_type = '{}-rest'.format(rest_type)
            augmentation = None
            if dots == 1:
                augmentation = 'augmented'
            elif dots == 2:
                augmentation = 'double-augmented'
            elif dots == 3:
                augmentation = 'triple-augmented'

        chord[self.DIM_CHORD_LOC + self.CHORD_CLASS_ENC.index(rest_type)] = 1
        if augmentation is not None:
            chord[self.DIM_CHORD_LOC + self.CHORD_CLASS_ENC.index(augmentation)] = 1
        return chord

    ### generates a note "chord" with no notes added yet
    def __get_note_chord__(self, measure_nbr, part_nbr, staff_nbr, voice_nbr, note_type=None, dots=0, duration=0):
        chord = self.__new_chord__()
        chord[0], chord[1], chord[2], chord[3] = part_nbr, staff_nbr, measure_nbr, voice_nbr
        if note_type is None:
            note_type, augmentation = self.__get_note_type__(duration, rest=False)
        else:
            note_type = '{}-note'.format(note_type)
            augmentation = None
            if dots == 1:
                augmentation = 'augmented'
            elif dots == 2:
                augmentation = 'double-augmented'
            elif dots == 3:
                augmentation = 'triple-augmented'
        
        chord[self.DIM_CHORD_LOC + self.CHORD_CLASS_ENC.index(note_type)] = 1
        if augmentation is not None:
            chord[self.DIM_CHORD_LOC + self.CHORD_CLASS_ENC.index(augmentation)] = 1
        return chord

    ### adds a note pitch to a particular chord
    def __add_note_pitch__(self, chord, part_nbr, staff_nbr, note_letter, octave, accidental=None):
        channels_i = self.DIM_CHORD_LOC + self.DIM_CHORD_CLASS
        channels = chord[channels_i : channels_i + self.DIM_CHANNEL*self.NBR_CHANNELS]
        open_channels_i = []
        for i in range(self.NBR_CHANNELS):
            channel = channels[i*self.DIM_CHANNEL : (i+1)*self.DIM_CHANNEL]
            if False not in [v == 0 for v in channel]:
                open_channels_i.append(i)
        if len(open_channels_i) == 0:
            print('ERROR: too many notes for 8-channel chord')
            print('failed to add a {} (octave={}) to chord'.format(note_letter, octave))
        else:
            channel_i = open_channels_i[0]*self.DIM_CHANNEL
            channel = channels[channel_i : channel_i + self.DIM_CHANNEL]
            clef_type, clef_line, octave_shift = self.clef_tracker[part_nbr][staff_nbr]
            pitch = self.__get_pitch__(note_letter, octave, clef_type, clef_line, octave_shift)
            encoded = [1,pitch,0,0,0,0,0]
        if accidental:
            encoded[self.NOTE_ENC.index(accidental)] = 1
        chord[channels_i+channel_i : channels_i+channel_i*self.DIM_CHANNEL] = encoded


    ### returns pitch of note as integer based on the staff line position (line 3 is 0, line 4 is 1, etc.)
    def __get_pitch__(self, note_letter, octave, clef_type, clef_line, octave_shift):
        note_letter, clef_type = note_letter.upper(), clef_type.upper()
        octave, clef_line, octave_shift = int(octave),int(clef_line),int(octave_shift)

        # get pitches and shifts of the cleff-line
        clef_note = clef_type.split('-')[0]
        additional_octave_shift = 1 if (self.NOTE[clef_note] - clef_line - 1)*2 < 0 else 0
        clef_abs_pitch = self.NOTE[clef_type] \
                            + (7 * (self.CLEF_OCTAVE[clef_type] + octave_shift + additional_octave_shift))
        clef_pitch = (clef_line - 3) * 2
        clef_pitch_diff = clef_abs_pitch - clef_pitch

        # get pitch of note
        note_abs_pitch = self.NOTE[note_letter] + (7 * octave)
        note_pitch = note_abs_pitch - clef_pitch_diff
        
        return note_pitch

    ### returns the class name of the note given the duration (based on current beat_division per part #)
    def __get_note_type__(self, part_nbr, duration, rest=False):
        print('starting __get_note_type__')
        postfix = 'note' if not rest else 'rest'
        divisions = self.beat_divisions[part_nbr]
        beat_unit = 4*divisions
        beats, beat_type = self.time_tracker[part_nbr]
        scaled_dur = duration/divisions
        note_type, order = '', 0

        if Encoder.__equal__(scaled_dur, float(beats)/float(beat_type)):# measure fill note/rest (whole note/rest)
            note_type, order = 'whole', 0
        if Encoder.__equal__(scaled_dur, 1) or scaled_dur > 1:          # whole note/rest
            note_type, order = 'whole', 1
        elif Encoder.__equal__(scaled_dur, 1/2) or scaled_dur > 1/2:    # half note/rest
            note_type, order = 'half', 2
        elif Encoder.__equal__(scaled_dur, 1/4) or scaled_dur > 1/4:    # quarter note/rest
            note_type, order = 'quarter', 3
        elif Encoder.__equal__(scaled_dur, 1/8) or scaled_dur > 1/8:    # 8th note/rest
            note_type, order = '8th', 4
        elif Encoder.__equal__(scaled_dur, 1/16) or scaled_dur > 1/16:  # 16th note/rest
            note_type, order = '16th', 5
        elif Encoder.__equal__(scaled_dur, 1/32) or scaled_dur > 1/32:  # 32nd note/rest
            note_type, order = '32nd', 6
        elif Encoder.__equal__(scaled_dur, 1/64) or scaled_dur > 1/64:  # 64 note/rest
            note_type, order = '64th', 7
        else:
            note_type = 'unknown'                                         # note of unknown duration
            print('could not match note with duration {} to note type'.format(duration))

        if order > 0:
            rem = scaled_dur - scaled_dur/pow(2,order-1)
            augmentations = 0
            while not Encoder.__equal__(rem, 0):
                print('note_type, order = {}, {}'.format(note_type, order))
                print('scaled_dur={}'.format(scaled_dur))
                print('  sub = {}'.format(1/pow(2,order-1)))
                print('     rem = {}'.format(rem))
                input()
                rem -= 1/pow(2,order)
                augmentations += 1
                order += 1
                if rem < -0.0001:
                    print('error determining note augmentation')

        # convert note_type and augmentations to note/rest class names
        note_type_name = '{}-{}'.format(note_type, postfix)
        aug_name = None
        if augmentations == 1:
            aug_name = 'augmented'
        elif augmentations == 2:
            aug_name = 'double-augmented'
        elif augmentations == 3:
            aug_name = 'triple-augmented'

        return note_type_name, aug_name

    def __get_duration__(self, note_type):
        name = note_type.split('-')[0]
        duration = 0
        if name == 'whole':
            duration = 1
        elif name == 'half':
            duration = 1/2
        elif name == 'quarter':
            duration = 1/4
        elif name == 'eighth':
            duration = 1/8
        elif name == '16th':
            duration = 1/16
        elif name == '32nd':
            duration == 1/32
        elif name == '64th':
            duration == 1/64
        return duration
        

    def __pitches__(self, chord):
        channels = chord[self.DIM_CHORD_LOC + self.DIM_CHORD_CLASS : len(chord)]
        pitches = []
        for i in range(self.NBR_CHANNELS):
            pitch, accidental = None, ''
            channel = channels[i*self.DIM_CHANNEL : (i+1)*self.DIM_CHANNEL]
            if 1 in channel:
                pitch = channel[1]
                if 1 in channel[2:]:
                    accidental = self.NOTE_ENC[2 + channel[2:].index(1)]
            if pitch is not None:
                pitches.append((pitch, accidental))
        return pitches

    def __object_class__(self, chord):
        objects = chord[self.DIM_CHORD_LOC : self.DIM_CHORD_LOC + self.DIM_CHORD_CLASS]
        object_names = []
        for i, v in enumerate(objects):
            if v != 0:
                name = self.CHORD_CLASS_ENC[i]
                listed_name = name if name not in [
                    'clef-pitch','clef-octave-shift','time-signature-beats','time-signature-unit'
                ] else '{}={}'.format(name,v)
                object_names.append(listed_name)
        return object_names

    def __print__(self, chord):

        print(
            'part           {}\n'.format(chord[0]) +
            'staff          {}\n'.format(chord[1]) +
            'measure        {}\n'.format(chord[2]) +
            'voice          {}\n'.format(chord[3]) +
            'object-class   {}\n'.format(self.__object_class__(chord)) +
            'pitches        {}\n'.format(self.__pitches__(chord))
        )

    @staticmethod
    def __equal__(v1,v2,error_margin=0.00001):
        eq = False
        if abs(v1-v2) < error_margin:
            eq = True
        return eq


encoder = Encoder(os.path.join(MUSICXML_DIR,'major-test.musicxml'))
#encoder.__get_chords__(1)
for chord in encoder.__get_chords__(1):
    encoder.__print__(chord)
