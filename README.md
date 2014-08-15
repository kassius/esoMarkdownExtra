esotalk-markdownextra
=====================

Markdown Extra Plugin for esoTalk

Still have some little bugs.

## Installation

### 1. First method, getting the master .zip file

1. Download the file https://github.com/kassius/esotalk-markdownextra/archive/master.zip
2. Extract it to your [esoTalk instalation directory]/addons/plugins/
3. Rename the newly created directory 'esotalk-markdownextra-master' to 'MarkdownExtra'
4. Go to 'administration' on the site, then to 'Plugins' and enable 'Markdown Extra' plugin.
5. Then it should be working. Just create a post using Markdown syntax and test it.

### 2. Second method, cloning this repository to your plugins/ directory via command line

Via command line, commands are:

~~~bash
cd esoTalk/addons/plugins # go to plugins directory inside your esoTalk installation
git clone https://github.com/kassius/esotalk-markdownextra.git # clone the repository
mv esotalk-markdownextra MarkdownExtra # rename the repository do 'MarkdownExtra'
rm -r MarkdownExtra/.git # delete git files for safety, unless you want to update it later via command line, then restrict access to this directory in your server's configuration
~~~

Then it should be working. Just create a post using Markdown syntax and test it.

## Requirements

For now it requires that you have a version of PHP with the class autoloader enabled.

## TODO

- [ ] css for member controller, for showing members posts, should have diminuted headers.
- [x] ~~correct automatic links,ex.: &gt;http://esotalk.org&lt;~~ **use just url autolinks instead**,  
- [x] correct footnotes from being breaked
- [ ] choose a name for the plugin uri, ex.: esoMDExtra
- [ ] correct bug with md text link, href "" being escaped
- [ ] make a trigger for post formatting
- [ ] release with a version

## Reference

* Markdown
  * http://daringfireball.net/projects/markdown/
  * http://en.wikipedia.org/wiki/Markdown
* Markdown Extra
  * https://michelf.ca/projects/php-markdown/extra/
  * https://github.com/michelf/php-markdown/
* esoTalk Forum Software
  * https://esotalk.org/forum/
