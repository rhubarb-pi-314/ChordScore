# pdf-merger.py usage

Put all complete system PDF files into one folder and all incomplete PDF files into another. All complete PDF files must follow the naming convention **\[file id\]a.pdf** and for incomplete PDF files **\[file id\]b.pdf**.

Create a folder in which to store the merged PDF files that will be generated.

pdf-merger takes 3 command line arguments:
<br>\[1\] path to the folder containing complete PDF files
<br>\[2\] path to the folder containing incomplete PDF files
<br>\[3\] path to the folder in which merged PDF files will be stored

Dependencies:
- Python installation (3.6+ tested)
- Poppler for Windows [https://blog.alivate.com.au/poppler-windows/]
- Python packages Pillow and pdf2image ` pip install pillow ` and ` pip install pdf2image `

Example usage:
```python pdf-merger.py C:/.../pdf-complete C:/.../pdf-incomplete C:/.../merged-pdfs```