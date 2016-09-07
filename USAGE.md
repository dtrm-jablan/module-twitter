# Elasticsearch Integration Module
The library consists of five services, each providing different services. Each is available through its respective facade as through this module's container.

The five services are:

 * Elastic - Provides indexing and searching methods that operate on an array of data
 * ElasticQueue - Provides a queue service for background data indexing 
 * ElasticSearch - A low-level Elasticsearch interface that operates on document objects
 * ElasticSeeder - Provide a service that loads local database data into Elasticsearch for indexing
 * Tesseract - Provides OCR services for scanned documents
 
Each service has various methods as detailed below..

## Elastic
This is the main entry point to the module. Almost all Elasticsearch functionality can be used via this facade.

### Elastic::queue
### Elastic::index
### Elastic::search
### Elastic::query
### Elastic::delete
### Elastic::deleteById
### Elastic::info
### Elastic::rebuild
### Elastic::serviceSearchDataQueue
### Elastic::queueSearchData
### Elastic::dequeueSearchData
### Elastic::getIndex
### Elastic::remapIndex
### Elastic::createDocumentFromData
### Elastic::getIndexSettings
### Elastic::putIndexSettings

### Related Commands

* elastic:dequeue
* elastic:populate
* elastic:remap
* elastic:settings
* elastic:setup

## ElasticQueue
This service manages all aspects of the search data queue.

### ElasticQueue::push
### ElasticQueue::pop
### ElasticQueue::getQueuedItems
### ElasticQueue::clear
### ElasticQueue::markProcessed
### ElasticQueue::create
### ElasticQueue::service
### ElasticQueue::isDataEncrypted
### ElasticQueue::setEncryptData

### Related Commands

* elastic:dequeue
* elastic:setup

## ElasticSearch
This service interacts directly with the official Elasticsearch client library. Unlike the Elastic methods which work with arrays of data, this service only operates on document objects.  

### ElasticSearch::index
> public function index($document, $params = [])

### ElasticSearch::find
### ElasticSearch::get
### ElasticSearch::getSource
### ElasticSearch::search
### ElasticSearch::query
### ElasticSearch::delete
### ElasticSearch::deleteIndex
### ElasticSearch::upsert
### ElasticSearch::indexRemap
### ElasticSearch::getSettings
### ElasticSearch::putSettings
### ElasticSearch::exists
### ElasticSearch::info
### ElasticSearch::count
### ElasticSearch::termVectors
### ElasticSearch::putMapping

### Related Commands

* elastic:dequeue
* elastic:populate
* elastic:remap
* elastic:settings
* elastic:setup

## ElasticSeeder
Provides index seeding directly from the database.

### ElasticSeeder::seedTables
> public function seedTables($index = null, $tables = [], $output = null)

### Related Commands

* elastic:remap
* elastic:populate

## Tesseract
The main entry point to the Tesseract OCR service

### Tesseract::scan
> public function scan($document, $options = [])

Given a file name or document object, run it through the Tesseract OCR and return the extracted text.

If a document object is given, the extracted text will be placed back into the document as well as returned.

With a file name:

```
    $_fileName = '/tmp/some_file.pdf';
    $_text = Tesseract::scan($_fileName);
```

or with a document object:

```
    $_document = DocumentFactory::fromData('SOME_TABLE', $row)
    $_text = Tesseract::scan($_document);
    
    //  The document also has the extraction
    $_docText = $_document->getExtractedText();
    
    //  and right into Elasticsearch...
    ElasticSearch::index($_document);

```

The Tesseract OCR service cannot process PDF files. If you pass in a PDF file, it will be converted to TIFF image format and then scanned. Generally, PDF files do not need to use this service because they already contain their text. It is only PDF files of scanned documents that will benefit from this service.

### Related Commands

* elastic:scan
