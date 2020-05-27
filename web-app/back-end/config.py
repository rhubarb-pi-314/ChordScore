""" Configure to system directory and run before running other Python modules
"     Recommended directory structure under HOME directory
"         HOME
"           png-raw         // initially contains music image PNG files
"           jpg-raw         // initially empty
"           jpg-resized     // initially empty
"           mxl             // initially contains music mxl files (if any)
"           musicxml        // initially contains musicxml files (or empty if mxl files provided instead)
"           ground-truth    // initially empty
"           program-data    // initially empty
"           metadata        // initially empty
"""

import os

HOME = 'drive/My Drive/ChordScore Folder/BIG data'

PNG_DIR            = os.path.join(HOME, 'png-raw')
# directory for full-size png files
#

JPG_DIR            = os.path.join(HOME, 'jpg-raw')
# directory for full-size jpg files
# files converted from png format are generated here
#

JPG_RESIZED_DIR    = os.path.join(HOME, 'jpg-resized')
# directory for resized jpg files when they are
# generated from the full-size jpg files
# (aspect ratio maintained)
#

MXL_DIR            = os.path.join(HOME, 'mxl')
# directory for compressed musicxml files
#

MUSICXML_DIR       = os.path.join(HOME, 'musicxml')
# directory for raw musicxml files
# files extracted from mxl format are generated here
#

GROUND_TRUTH       = os.path.join(HOME, 'ground-truth')
# directory for files containing the "ground truth"
#   array for a piece of music
# the ground truth array is an array of chords with the
#   following information extracted from the musicxml file...
#     1) measure  = measure.number
#     2) staff    = part.id-1 * (# of staves in prev part) + staff.text
#     3) voice    = voice.text (encoded to integer range 1,2,3,... # of voices)
#     4) chord    = 84 values
#

PROGRAM_DATA       = os.path.join(HOME, 'program-data')
# directory for temporary directories and intermediate files
#

METADATA_DIR       = os.path.join(HOME, 'metadata')
# directory for data metadata files
#
