# qTranslate addons

Add features and fixes to qTranslate and qTranslate slug.

## Description

Frontend ajax requests language support / Events manager fields translation support / Fix requested url in path mode, and more to come.

## Requirements
 * WordPress 3.5 (tested up to 3.8.1)
 * PHP 5

## Installation

### Upload

1. Download the latest tagged archive (choose the "zip" option).
2. Go to the __Plugins -> Add New__ screen and click the __Upload__ tab.
3. Upload the zipped archive directly.
4. Go to the Plugins screen and click __Activate__.

### Manual

1. Download the latest tagged archive (choose the "zip" option).
2. Unzip the archive.
3. Copy the folder to your `/wp-content/plugins/` directory.
4. Go to the Plugins screen and click __Activate__.

Check out the Codex for more information about [installing plugins manually](http://codex.wordpress.org/Managing_Plugins#Manual_Plugin_Installation).

### Git

Using git, browse to your `/wp-content/plugins/` directory and clone this repository:

`git clone git@github.com:medfreeman/qtranslate-addons.git`

Then go to your Plugins screen and click __Activate__.

## Usage

Nothing to do.

A class name is automatically appended to the body HTML element, that is 'currentlang-XX' where XX is the language code.

Ajax requests are automatically appended the 'lang' argument with the current language, so as to load the environment in the current user frontend language.

If Events Manager plugin is enabled, events and locations titles, contents and excerpts are automatically translated according to your input and the current language.

## Update
This plugin can be auto-updated via git with this plugin : https://github.com/afragen/github-updater

## Planned

Add function that returns 'real' url in pre-path mode, because $_SERVER['REQUEST_URI'] doesn't contain the language suffix, useful when you need to manually change url, add_query_arg, remove_query_arg, ...
The trick with $_SERVER['REDIRECT_URL'] is bad, because environment dependent..

qTranslate slug autocomplete when no value is entered ( working like original wordpress function with 'real' permalinks that work ) when post / cpt / taxonomy is saved in admin.
