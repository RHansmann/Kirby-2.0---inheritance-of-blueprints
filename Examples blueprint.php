The merge command in a blueprint file can be specified in different ways 
depending on how many other blueprint files should be embbed. 

In general the blueprint files to embed follow the same rules and 
settings as normal blueprint files. This means that they may contain 
any parameter allowed for blueprints.

----------------------------------------------------------------------
Variant 1:  use the merge parameter with a file specified in the 
            same line as the merge parameter. In this case only 1 
            other blueprint file can be merged with current blueprint 
            file.
            Existing parameters will be overwritten or - if
            multiple values allowed - merged, new parameters will be
            appended to the end of their according collection.
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
Variant 2:  merging more than 1 blueprint file can be executed by
            specifying several file names of blueprint files to merge
            in subsequent lines ot the merge parameter.
            Merging follows same rules as specified in variant 1.
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
  filename2mergewithoutextension_1 
  filename2mergewithoutextension_2

----------------------------------------------------------------------
Variant 3:  same variant as specified in variant 2 but the place 
            where new items should be placed can be specified as a
            value to the filename. Following values are allowed:
            
            begin: place new items at the beginning of the according
                   collection
            end:   append new items at the end of the according
                   collection
            
            Mixing variant 2 and 3 is allowed for the merge parameter.
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
    filename2mergewithoutextension_1: begin 
    filename2mergewithoutextension_2: end 
    filename2mergewithoutextension_3            (= variant 2: always treated as "end")
    filename2mergewithoutextension_4: end
    filename2mergewithoutextension_5: begin
