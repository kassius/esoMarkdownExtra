esotalk-markdownextra
=====================

Markdown Extra Plugin for esoTalk

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
- [x] ~~correct automatic links~~, **autolinks**, ex.: <http://esotalk.org>
- [x] correct footnotes from being breaked
- [ ] choose an name for the plugin uri, ex.: esoMDExtra
