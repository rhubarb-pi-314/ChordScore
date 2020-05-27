from config import *
from zipfile import ZipFile
import shutil

def unpack(mxl_dir, musicxml_dir, temp_dir):
    for fname in os.listdir(mxl_dir):
        #try:
        src_path = os.path.join(mxl_dir, fname)
        dst_path = os.path.join(musicxml_dir, fname.replace('.mxl', '.musicxml'))
        tmp = os.path.join(temp_dir,'temp-{}-extractor'.format(fname))
        if not os.path.isdir(tmp):
          os.mkdir(tmp)
        with ZipFile(src_path, 'r') as xtractor:
            xtractor.extractall(tmp)
        print(os.listdir(tmp))
        xtracted_file = [os.path.join(tmp, f) for f in os.listdir(tmp) if os.path.isfile(os.path.join(tmp, f))][0]
        os.rename(xtracted_file, dst_path)
        shutil.rmtree(tmp)
        #except Exception as e:
        #    print('skipped {}. error was {}'.format(fname, e))

unpack(MXL_DIR,MUSICXML_DIR,PROGRAM_DATA)
