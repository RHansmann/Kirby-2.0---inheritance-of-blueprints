merge command in a blueprint file can be specified in different ways depending on how many files should be embbed:

----------------------------------------------------------------------
Variant 1:  merge parameter including the filename to merge with the
            current blueprint file - only 1 other blueprint file 
            should be merged with current blueprint file
            (new pages, files and/or fields will be appended to end 
             the according collection)
----------------------------------------------------------------------
<?php if(!defined('KIRBY')) exit ?>
title: Page
pages: true
files: true
fields:
  title:
    label: Title
    type:  textarea
  text:
    label: Text
    type:  textarea
merge: filename2mergewithoutextension

----------------------------------------------------------------------
Variant 2:  merge more than 1 other blueprint file using the sub-
            parameters "first" and "last" of parameter "merge" and
            specifying the files to merge on the same line
            (new items of files specified for "first" parameter will
            be inserted at beginning of their according collection of 
            the current blueprint file, fields for the "last" 
            parameter will be appended to the end to the end of the
            collection)
----------------------------------------------------------------------
<?php if(!defined('KIRBY')) exit ?>
title: Page
pages: true
files: true
fields:
  title:
    label: Title
    type:  textarea
  text:
    label: Text
    type:  textarea
merge: 
  begin:  filename2mergewithoutextension_1 
  end:    filename2mergewithoutextension_2

----------------------------------------------------------------------
Variant 3:  merge more than 1 other blueprint file using sub-
            parameters (filenames) for the "begin" and "end" parameter
            (new items of files specified for "first" parameter will
            be inserted at beginning of their according collection of 
            the current blueprint file, fields for the "last" 
            parameter will be appended to the end to the end of the
            collection)
----------------------------------------------------------------------
<?php if(!defined('KIRBY')) exit ?>
title: Page
pages: true
files: true
fields:
  title:
    label: Title
    type:  textarea
  text:
    label: Text
    type:  textarea
merge: 
  begin: 
    filename2mergewithoutextension_1 
    filename2mergewithoutextension_2 
    filename2mergewithoutextension_3 
  end:
    filename2mergewithoutextension_4
    filename2mergewithoutextension_5
