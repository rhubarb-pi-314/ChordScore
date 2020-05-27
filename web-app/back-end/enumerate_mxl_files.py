import os, json
from config import *

file_ids = os.path.join(METADATA_DIR,'file-ids.json')
if os.path.isfile(file_ids):
  with open(file_ids,'r') as fr:
    ids = json.load(fr)
else:
  ids = {}

current_id = 1 if len(ids.keys()) == 0 else max([int(i) for i in ids.keys()])+1
for fname in os.listdir(MXL_DIR):
  base = fname.split('.mxl')[0]
  if not base.isdigit():
    new_name = '{}.mxl'.format(str(current_id))
    ids[current_id] = fname
    current_id += 1
    os.rename(os.path.join(MXL_DIR,fname),os.path.join(MXL_DIR,new_name))

with open(file_ids,'w') as fw:
  json.dump(ids, fw)
