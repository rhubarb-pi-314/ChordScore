from config import *
from PIL import Image
import os

### data preprocessing
# resize image to W x H ratio W : H = 8.5 : 11
#    absolute size: W = 850, H = 1100
def resize_imgs(img_dir, res_img_dir, w, h):
  for fname in [f for f in os.listdir(img_dir)
                    if len(f) > 4 and f[len(f)-4:] in ['.jpg','.png']]:
    img_fpath = os.path.join(img_dir, fname)
    print('Resized image file {}'.format(fname))
    img = Image.open(img_fpath)
    img = img.resize((w, h))
    img.save(os.path.join(res_img_dir, fname))

w = 750
h = int(w * (11/8.5))
resize_imgs(JPG_DIR, JPG_RESIZED_DIR, w, h)
