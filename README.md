vivo-tools
==========

This will hold my toolset for working with VIVO, SPARQL, and RDF

vivoCSV
=======

You will need to have a fully qualified SPARQL query in a file that you can input into the script for this to run. You will also need access to
a SPARQL endpoint (tested with Fuseki), and PHP CLI installed on your machine. You should chmod the file to allow execution of the php file, and
then you can just type:

vivoCSV tool usage:
-u The URL to your SPARQL endpoint to include the query designtation - ie 'http://sparql.vivo.ufl.edu:3030/VIVO/query?query='
-s The path to the file containing your SPARQL query
-o The path to the file you want to output to

EXAMPLE
vivoCSV.php -u http://sparql.vivo.ufl.edu:3030/VIVO/query?query= -s ./sparqlQuery.sparql -o ./nameOfCSV.csv

This command will use the Fuseki endpoint and pass it the sparql query found in the sparqlQuery.sparql file located in the same directory as the php
file. It will then output to a CSV named nameOfCSV.csv in the same directory as the php file. This is made fairly modular, and should support any
SPARQL SELECT query.

I have the limit of the publications query set to 10000 returned items. I tried to take the limit off, but ran PHP out of memory (I had set it up to
1GB)

vivoRDFExport
=============

You will need to have a fully qualified SPARQL query in a file. This will normally be used for construct statements, as we are looking to output XML
not work with the XML within PHP.

vivoRDFExport tool usage:
-u The URL to your SPARQL endpoint to include the query designtation - ie 'http://sparql.vivo.ufl.edu:3030/VIVO/query?query='
-s The path to the file containing your SPARQL query
-o The path to the file you want to output to
-p The path to the parameter CSV file you want to use to fill out your query

EXAMPLE
vivoRDFExport.php -u http://sparql.vivo.ufl.edu:3030/VIVO/query?query= -s ./personRDF.sparql -o ./personRDF.xml -p ./ufids.csv

