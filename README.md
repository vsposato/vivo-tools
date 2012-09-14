vivo-tools
==========

This will hold my toolset for working with VIVO, SPARQL, and RDF

vivoCSV
=======

You will need to have a fully qualified SPARQL query in a file that you can input into the script for this to run. You will also need access to
a SPARQL endpoint (tested with Fuseki), and PHP CLI installed on your machine. You should chmod the file to allow execution of the php file, and
then you can just type:

vivoCSV.php -u http://sparql.vivo.ufl.edu:3030/VIVO/query?query= -s ./sparqlQuery.sparql -o ./nameOfCSV.csv

This command will use the Fuseki endpoint and pass it the sparql query found in the sparqlQuery.sparql file located in the same directory as the php
file. It will then output to a CSV named nameOfCSV.csv in the same directory as the php file. This is made fairly modular, and should support any
SPARQL SELECT query.

I have the limit of the publications query set to 10000 returned items. I tried to take the limit off, but ran PHP out of memory (I had set it up to
1GB)

