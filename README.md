ZweBox
===========

ZweBox is yet another clone of the incredible [Lightbox](http://www.lokeshdhakar.com/projects/lightbox2/), but this time using the [MooTools](http://mootools.net) framework.

If you don't know anything about Lightbox, this is beautiful and easy way to show images, galleries, videos and html content in a way similar to the old-fashioned PopUp, but with some better visual effects!

In this particular version of Lightbox I've taken a BIG inspiration from the JQuery script [JQuery Lightbox Evolution](http://codecanyon.net/item/jquery-lightbox-evolution/115655) by [Eduardo Sada](http://codecanyon.net/user/aeroalquimia). I've rewritten everything from Eduardo with MooTools in mind and making some improvements such as resizing after having resized the window, gallery not only of images and mutch more. I also give complete credit to Eduardo for what concerns the stylesheets (I've only made one or two modifications to match the new gallery way).

This script uses the version 1.3 of MooTools, and I've worked with the full library included. (In a future momento I'll try to write an "include only" list.

Hot to use
-------------------

First of all you have to include the MooTools library, both the core and the more ones.

        #HTML
        <script type="text/javascript" src="mootools-core.js"></script>
        <script type="text/javascript" src="mootools-more.js"></script>

Then you can include the script dependencies: [Overlay.js](https://github.com/darkwing/Overlay) by [David Walsh](http://davidwalsh.name/)

        #HTML
        <script type="text/javascript" src="Overlay.js"></script>

Ok, everything is ready to include the script...

        #HTML
        <script type="text/javascript" src="../Source/zwebox.js"></script>

...and the style (the second one is the ie6 fix).

        #HTML
        <link type="text/css" rel="stylesheet" href="../Source/themes/default/zwebox.css" />
        <!--[if IE 6]>
        <link rel="stylesheet" type="text/css" href="../Source/themes/default/zwebox.ie6.css" />
        <![endif]-->

Obviously you can change the style by selecting another css!


### CSS

ZweBox doesn't style anything with Javascript (setStyle[s] method is only used to show/hide elements). You can change anything in the css files!

(Note: ZweBox is designed to allow more instances on the same page. Because of this, you can style theme in different ways. The default css has been written using the default name of the instance. If you want to change the name, remember to change also the name of the classes in the css files!)