This filter is a more flexible version of multilang filter.
It has an admin setting filter_multilangsecond_mode. 
If this setting is set to true non html syntax can be used for multilang blocks like

{mlang en}English{mlang}{mlang bg}Bulgarian{mlang}

Otherwise language block is a serie of identical html tags with lang="XX" atributes:

<h1 lang="en">Heading in English</h1>
<h1 lang="bg">Heading in Bulgarian</h1>

or

<p lang="en" style="...">Paragraph in English</p>
<p lang="bg" class="...">Paragraph in Bulgarian</p>
<p lang="ro">Paragraph in Romanian</p>

The old syntax with <lang> tags is valid too.
