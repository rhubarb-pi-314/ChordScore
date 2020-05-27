# ChordScore (Backend)

The ChordScore backend will be able to take images submitted by students and return a grade for the assignment. Homework music notation images will be graded based on a CRNN (Convolutional Recurrent Neural Network). The network will be trained with images as input and encoded vectors from MusicXML files as output. The backend scripts take three file types as input for preparing data with which to train the CRNN - music notation images scaled to one page (PNG), notation object bounding box data for each image (XML), and corresponding mxl files (compressed MusicXML files; not necessarily separated by page). 

## Data Preparation Process
(more info to come)

### 1) Enumerate file names
(more info to come)

### 2) Decompress mxl files to MusicXML format
(more info to come)

### 3) Extract and encoded bounding box data
(more info to come)

### 4) Generate encoded ground truth vectors from MusicXML files
(more info to come)

## CRNN Training
(more info to come)

### STAGE 1 - Music object detection
(more info to come)

### STAGE 2 - End-to-end chord detection
(more info to come)
