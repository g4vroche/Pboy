
PBoy 
====

An attempt to create a basic static blog generator in PHP.

Wrok in progress, already functional.

[![Scrutinizer Quality Score](https://scrutinizer-ci.com/g/g4vroche/Pboy/badges/quality-score.png?s=e6b1d9c527f9e5b3982dfab32089cbae7c5cafd9)](https://scrutinizer-ci.com/g/g4vroche/Pboy/)

[![Code Coverage](https://scrutinizer-ci.com/g/g4vroche/Pboy/badges/coverage.png?s=81e5c4dc79e407724c63d45e7104a5fc6aef4859)](https://scrutinizer-ci.com/g/g4vroche/Pboy/)




Quick start :
-------------

Create a new post

    $ php pboy post

Generate blog
    
    $php pboy generate




Features aimed :
----------------

Generates full blog from input

Input may be text files with restructuredText or markdown for example.
Plugin mechnism allow to add differents sources and formats

Items fetching :
 - File system
 - ?

Items parsing :
 - restructuredText *(going to be hard with PHP)*
 - markdown
 - wiki ?
 - BBcode ?
 - Other ? 


Rendering :
 - raw PHP
 - Twig ?
 - Other ?

Syntax highlighting
 - JS based ?

Versionning
 - Git plugin ?
 - SVN plugin ?


Assets optimisation : Join + Minification
 - ?


Multiple outputs for 
 - item list (HTML, RSS, whatever)
 - item view (blog post)

Allows you to generate : 
 - home / index page
 - tags pages
 - ?


Constraints
-----------

* Try being complient with PSR
* Keep this basic but extensible (but basic)
* Really easy setup
* Minimize dependencies


