SculpinSearchBundle
=====================

This bundle provides a search engine using indextank service for your static site.

You can use the service [Indexden](http://indexden.com/).

Installation
------------

Using composer, add the dependancy to your composer.json :

``` json
require: {
    "jbouzekri/sculpin-search-bundle": "1.*"
}
```

And run the composer update command

Enable the bundle. If you have already have an app/SculpinKernel.php, add this bundle to it otherwise create the file with the following content :

``` php
<?php

class SculpinKernel extends \Sculpin\Bundle\SculpinBundle\HttpKernel\AbstractKernel
{
    protected function getAdditionalSculpinBundles()
    {
        return array(
            'Jb\Bundle\SearchBundle\JbSearchBundle'
        );
    }
}
```

Then you need to configure the indextank service in sculpin_kernel.yml :

``` yml
jb_search:
    options:
        url: http://login.api.indexden.com
        user: "Your login here"
        password: "Your password here"
        index: "The name of your index"
```

If you use indexden, you can use the private url to fill this parameters : http://:password@login.api.indexden.com

Usage
-----

On each post you want to index, add the indexed: true data in the markdown part of the file.

``` md
---
indexed: true
---
```

You can now regenerate your site.

How it works
------------

An event listener is bind to the afterRun event. It indexes in indextank all documents marked with the indexed flag.

When indexing, it clears the index and bulk add the selected sources so it can take some times to generate the site when you index a lot of document.

Because of that, I added a generate command which wrap the original sculpin generate one in order to add an option no-index. When runing this command, you can work
on your static site and the content will not be indexed each time you change something in a file. (However do not forget to launch the generate command without this
parameter at the end to update your index in the search service).

``` bash
$ php sculpin generate-search --server  --watch --no-index
```

Configuration Reference
-----------------------

``` yml
jb_search:
    enabled: true # enable the indexation at the end of the generate task
    engine: indextank # change the engine (for now only indextank is supported)
    options:
        url: http://login.api.indexden.com
        user: "Your login here"
        password: "Your password here"
        index: "The name of your index"
```

License
-------

[MIT](LICENSE)
