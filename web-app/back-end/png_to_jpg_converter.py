from config import *
from PIL import Image
import os, numpy

### Convert images in dir from png to jpg
def png_to_jpg(png_dir, jpg_dir):
  for fname in os.listdir(png_dir):
    fpath = os.path.join(png_dir, fname)
    dst_fpath = os.path.join(jpg_dir, fname.replace('.png','.jpg'))

    im = Image.open(fpath)
    x = numpy.array(im)
    r,g,b,a = numpy.rollaxis(x,axis=-1)
    r[a == 0] = 255
    g[a == 0] = 255
    b[a == 0] = 255
    x = numpy.dstack([r,g,b])

    img_jpg = Image.fromarray(x)
    img_jpg.save(dst_fpath,'JPEG')

    print('finished converting {} to JPG'.format(fname))

png_to_jpg(PNG_DIR, JPG_DIR)
