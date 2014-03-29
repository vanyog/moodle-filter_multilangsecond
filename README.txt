This filter is a more flexible version of Moodle multilang filter.

It has an admin setting filter_multilangsecond_mode. 

One of three possible choices can be selected by this setting:

* HTML syntax
* Non HTML syntax or
* Both

In case of HTML syntax the language block is a sequence of identical 
html tags with lang="XX" attributes:

<h1 lang="en">Heading in English</h1>
<h1 lang="bg">Heading in Bulgarian</h1>

or

<p lang="en" style="...">Paragraph in English</p>
<p lang="bg" class="...">Paragraph in Bulgarian</p>
<p lang="ro">Paragraph in Romanian</p>

The old syntax with <lang> tags is valid too.

In case of Non HTML syntax the language block looks like this:

{mlang en}English{mlang}{mlang bg}Bulgarian{mlang}

Each language dependent string is enclosed by {mlang xx} and {mlang} 
elements. Between closing {mlang} element and the next opening 
{mlang yy} element must not be any other symbols, or html tags. Avoid 
to insert spaces, line or paragraph brakes between {mlang}{mlang yy} 
elements in language blocks.

When the Both option is chosen for filter_multilangsecond_mode setting, 
the filter processes the strings twice. First time to replace the non 
HTML language blocks and second time to replace the HTML language 
blocks. For performance reasons choose Both option only if you really 
need both syntaxes.

