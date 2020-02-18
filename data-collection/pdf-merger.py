from pdf2image import convert_from_path
import os, sys, random
from PIL import Image


if __name__ == '__main__':
    expected_argv = {
        "1" : "path to folder containing complete system PDF files (system a)",
        "2" : "path to folder containing incomplete system PDF files (system b)",
        "3" : "path to folder where merged PDF files will be stored"
    }

    if len(sys.argv) != 4:
        print("Too many arguments. Expected..." if len(sys.argv) > 4 else "Too few arguments. Expected...")
        for k in sorted(expected_argv.keys()):
            print("    argv[{}] - {}".format(k, expected_argv[k]))
        sys.exit(-1)

    pdf_a_dir, pdf_b_dir, pdf_full_dir = sys.argv[1:]
    tmp_jpg = os.path.join(pdf_full_dir, 'tmp')
    os.mkdir(tmp_jpg)

    full_dpi = 360
    full_xdim = 2812
    full_ydim = 3639

    dpi = {name.replace('a.pdf', '') : random.randint(250, full_dpi) for name in os.listdir(pdf_a_dir)}

    print("extracting images from complete PDF files...")
    for fname in os.listdir(pdf_a_dir):
        fpath = os.path.join(pdf_a_dir, fname)
        fid = fname.replace('a.pdf', '')
        image = convert_from_path(fpath, dpi[fid])[0]
        xdim, ydim = image.size
        crop_box = (0, 0, xdim, int(ydim/2))
        image = image.crop(crop_box)
        namebase, ext = os.path.splitext(fname)
        image.save(os.path.join(tmp_jpg, '{}.jpg'.format(namebase)), 'JPEG')
    print("    finished")

    print("extracting images from incomplete PDF files...")
    for fname in os.listdir(pdf_b_dir):
        fpath = os.path.join(pdf_b_dir, fname)
        fid = fname.replace('b.pdf', '')
        image = convert_from_path(fpath, dpi[fid])[0]
        xdim, ydim = image.size
        crop_box = (0, 0, xdim, int(ydim/2))
        image = image.crop(crop_box)
        namebase, ext = os.path.splitext(fname)
        image.save(os.path.join(tmp_jpg, '{}.jpg'.format(namebase)), 'JPEG')
    print("    finished")

    print("merging images into full PDF files...")
    for fname in os.listdir(pdf_a_dir):
        fname_a, fname_b = fname.replace('a.pdf', 'a.jpg'), fname.replace('a.pdf', 'b.jpg')
        fname_full = fname.replace('a.pdf', '.pdf')
        fpath = os.path.join(pdf_full_dir, fname_full)

        image = Image.new('RGB',(full_xdim, full_ydim),(255, 255, 255))
        imga = Image.open(os.path.join(tmp_jpg, fname_a), 'r')
        imgb = Image.open(os.path.join(tmp_jpg, fname_b), 'r')
        xpad_imga = int((full_xdim - imga.size[0])/2)
        ypad_imga = int((int(full_ydim/2) - imga.size[1])/2)
        xpad_imgb = int((full_xdim - imgb.size[0])/2)
        ypad_imgb = int((int(full_ydim/2) - imgb.size[1])/2)

        image.paste(imga, (xpad_imga, ypad_imga))
        image.paste(imgb, (xpad_imgb, ypad_imgb + int((full_ydim)/2)))
        image.save(fpath, 'PDF')
    print("    finished")

    # clean up - delete tmp folder
    for fname in os.listdir(tmp_jpg):
        fpath = os.path.join(tmp_jpg, fname)
        os.remove(fpath)
    while not len(os.listdir(tmp_jpg)) == 0:
        pass    # waiting for all files to be deleted
    os.rmdir(tmp_jpg)
