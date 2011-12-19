<?php

return array(

    # Zend_Captcha_Word
    Zend_Captcha_Word::MISSING_VALUE => "Il valore captcha è vuoto ma richiesto",
    Zend_Captcha_Word::MISSING_ID => "Il campo captcha ID risulta essere mancante",
    Zend_Captcha_Word::BAD_CAPTCHA => "Il valore captcha è sbagliato",

    # Zend_Captcha_ReCaptcha
    Zend_Captcha_ReCaptcha::MISSING_VALUE => "Manca il campo captcha",
    Zend_Captcha_ReCaptcha::ERR_CAPTCHA => "Fallita la validazione del captcha",
    Zend_Captcha_ReCaptcha::BAD_CAPTCHA => "Il valore captcha è sbagliato: %value%",

    # Zend_Validate_Alnum
    "alnumInvalid" => "Tipo di dato non valido: il dato dev'essere di tipo float, stringa o intero.",
    "notAlnum" => "'%value%' contine caratteri che non sono alfanumerici",
    "alnumStringEmpty" => "'%value%' è una stringa vuota",

    # Zend_Validate_Alpha
    "alphaInvalid" => "Tipo di dato non valido, il dato dev'essere una stringa",
    "notAlpha" => "'%value%' contiene caratteri non alfabetici",
    "alphaStringEmpty" => "'%value%' è una stringa vuota",

    # Zend_Validate_Barcode
    "barcodeFailed" => "'%value%' non ha un checksum valido",
    "barcodeInvalidChars" => "'%value%' contiene caratteri non permessi",
    "barcodeInvalidLength" => "'%value%' non ha la lunghezza corretta di %length% caratteri",
    "barcodeInvalid" => "Tipo di dato non valido, il dato dev'essere una stringa",

    # Zend_Validate_Between
    "notBetween" => "'%value%' non è compreso tra '%min%' e '%max%', inclusi",
    "notBetweenStrict" => "'%value%' non è strettamente compreso tra '%min%' e '%max%'",

    # Zend_Validate_Callback
    "callbackValue" => "'%value%' non è valido",
    "callbackInvalid" => "Callback fallita, eccezione ritornata",

    # Zend_Validate_Ccnum
    "ccnumLength" => "'%value%' deve contenere tra 13 e 19 cifre",
    "ccnumChecksum" => "L'algoritmo di Luhn (checksum mod-10) è fallito su '%value%'",

    # Zend_Validate_CreditCard
    "creditcardChecksum" => "'%value%' sembra contenere un checksum non valido",
    "creditcardContent" => "'%value%' deve contenere solo cifre",
    "creditcardInvalid" => "Tipo di dato non valido, il dato dev'essere una stringa",
    "creditcardLength" => "'%value%' contiene un numero non valido di cifre",
    "creditcardPrefix" => "'%value%' proviene da un istituto non supportato",
    "creditcardService" => "'%value%' non sembra essere un numero di carta di credito valido",
    "creditcardServiceFailure" => "Un'eccezione è stata lanciata mentre si cercava di validare '%value%'",

    # Zend_Validate_Date
    "dateInvalid" => "Tipo di dato non valido, il dato dev'essere di tipo stringa, intero, array o Zend_Date",
    "dateInvalidDate" => "'%value%' non sembra essere una data valida",
    "dateFalseFormat" => "'%value%' non corrisponde al formato data '%format%'",

    # Zend_Validate_Db_Abstract
    "noRecordFound" => "Non è stato trovato una riga con valore %value%",
    "recordFound" => "E' già stata trovata una riga con valore %value%",

    # Zend_Validate_Digits
    "digitsInvalid" => "Tipo di dato non valido: il dato dev'essere di tipo float, stringa o intero.",
    "notDigits" => "'%value%' può contenere solo cifre",
    "digitsStringEmpty" => "'%value%' è una stringa vuota",

    # Zend_Validate_EmailAddress
    "emailAddressInvalid" => "Tipo di dato non valido, il dato dev'essere una stringa",
    "emailAddressInvalidFormat" => "'%value%' non è un indirizzo email valido nel formato base local-part@hostname",
    "emailAddressInvalidHostname" => "'%hostname%' non è un hostname valido nell'indirizzo email '%value%'",
    "emailAddressInvalidMxRecord" => "'%hostname%' non sembra avere un record MX DNS valido nell'indirizzo email %value%'",
    "emailAddressInvalidSegment" => "'%hostname%' non è in un segmento di rete routabile. L'indirizzo email '%value%' non può essere risolto nella rete pubblica.",
    "emailAddressDotAtom" => "'%localPart%' non può essere validato nel formato dot-atom",
    "emailAddressQuotedString" => "'%localPart%' non può essere validato nel formato quoted-string",
    "emailAddressInvalidLocalPart" => "'%localPart%' non è una local part valida nell'indirizzo email '%value%'",
    "emailAddressLengthExceeded" => "'%value%' supera la lunghezza consentita",

    # Zend_Validate_File_Count
    "fileCountTooMany" => "Troppi file, sono consentiti massimo '%max%' file ma ne sono stati passati '%count%'",
    "fileCountTooFew" => "Troppi pochi file, sono attesi minimo '%min%' file ma ne sono stato passati solo '%count%'",

    # Zend_Validate_File_Crc32
    "fileCrc32DoesNotMatch" => "Il file '%value%' non ha un hash crc32 tra quelli consentiti",
    "fileCrc32NotDetected" => "L'hash crc32 non può essere calcolato per il file dato",
    "fileCrc32NotFound" => "Il file '%value%' non può essere trovato",

    # Zend_Validate_File_ExcludeExtension
    "fileExcludeExtensionFalse" => "Il file '%value%' ha un'estensione invalida",
    "fileExcludeExtensionNotFound" => "Il file '%value%' non può essere trovato",

    # Zend_Validate_File_Exists
    "fileExistsDoesNotExist" => "Il file '%value%' non esiste",

    # Zend_Validate_File_Extension
    "fileExtensionFalse" => "Il file '%value%' ha un'estensione invalida",
    "fileExtensionNotFound" => "Il file '%value%' non può essere trovato",

    # Zend_Validate_File_FilesSize
    "fileFilesSizeTooBig" => "I file devono avere in totale una dimensione massima di '%max%' ma è stata rilevata una dimensione di '%size%'",
    "fileFilesSizeTooSmall" => "I file devono avere in totale una dimensione minima di '%min%' ma è stata rilevata una dimensione di '%size%'",
    "fileFilesSizeNotReadable" => "Uno o più file non possono essere letti",

    # Zend_Validate_File_Hash
    "fileHashDoesNotMatch" => "I file '%value%' non corrisponde agli hash dati",
    "fileHashHashNotDetected" => "Un hash non può essere valutato per il file dato",
    "fileHashNotFound" => "Il file '%value%' non può essere trovato",

    # Zend_Validate_File_ImageSize
    "fileImageSizeWidthTooBig" => "La larghezza massima consentita per l'immagine '%value%' è '%maxwidth%' ma è stata rilevata una larghezza di '%width%'",
    "fileImageSizeWidthTooSmall" => "La larghezza minima consentita per l'immagine '%value%' è '%minwidth%' ma è stata rilevata una larghezza di '%width%'",
    "fileImageSizeHeightTooBig" => "L'altezza massima consentita per l'immagine '%value%' è '%maxheight%' ma è stata rilevata un'altezza di '%height%'",
    "fileImageSizeHeightTooSmall" => "L'altezza minima consentita per l'immagine '%value%' è '%minheight%' ma è stata rilevata un'altezza di '%height%'",
    "fileImageSizeNotDetected" => "Le dimensioni dell'immagine '%value%' non possono essere rilevate",
    "fileImageSizeNotReadable" => "Il file '%value%' non può essere letto",

    # Zend_Validate_File_IsCompressed
    "fileIsCompressedFalseType" => "Il file '%value%' non è un file compresso, ma un file di tipo '%type%'",
    "fileIsCompressedNotDetected" => "Il mimetype del file '%value%' non può essere rilevato",
    "fileIsCompressedNotReadable" => "Il file '%value%' non può essere letto",

    # Zend_Validate_File_IsImage
    "fileIsImageFalseType" => "Il file '%value%' non è un'immagine, ma un file di tipo '%type%'",
    "fileIsImageNotDetected" => "Il mimetype del file '%value%' non può essere rilevato",
    "fileIsImageNotReadable" => "Il file '%value%' non può essere letto",

    # Zend_Validate_File_Md5
    "fileMd5DoesNotMatch" => "Il file '%value%' non corrisponde agli hash md5 dati",
    "fileMd5NotDetected" => "Un hash md5 non può essere valutato per il file dato",
    "fileMd5NotFound" => "Il file '%value%' non può essere trovato",

    # Zend_Validate_File_MimeType
    "fileMimeTypeFalse'" => "Il file '%value%' ha un mimetype invalido: '%type%'",
    "fileMimeTypeNotDetected" => "Il mimetype del file '%value%' non può essere rilevato",
    "fileMimeTypeNotReadable" => "Il file '%value%' non può essere letto",

    # Zend_Validate_File_NotExists
    "fileNotExistsDoesExist" => "Il file '%value%' esiste già",

    # Zend_Validate_File_Sha1
    "fileSha1DoesNotMatch" => "Il file '%value%' non corrisponde agli hash sha1 dati",
    "fileSha1NotDetected" => "Un hash sha1 non può essere valutato per il file dato",
    "fileSha1NotFound" => "Il file '%value%' non può essere trovato",

    # Zend_Validate_File_Size
    "fileSizeTooBig" => "La dimensione massima consentita per il file '%value%' è '%max%' ma è stata rilevata una dimensione di '%size%'",
    "fileSizeTooSmall" => "La dimensione minima consentita per il file '%value%' è '%min%' ma è stata rilevata una dimensione di '%size%'",
    "fileSizeNotFound" => "Il file '%value%' non può essere trovato",

    # Zend_Validate_File_Upload
    "fileUploadErrorIniSize" => "Il file '%value%' eccede la dimensione definita nell'ini",
    "fileUploadErrorFormSize" => "Il file '%value%' eccede la dimensione definita nella form",
    "fileUploadErrorPartial" => "Il file '%value%' è stato caricato solo parzialmente",
    "fileUploadErrorNoFile" => "Il file '%value%' non è stato caricato",
    "fileUploadErrorNoTmpDir" => "Non è stata trovata una directory temporanea per il file '%value%'",
    "fileUploadErrorCantWrite" => "Il file '%value%' non può essere scritto",
    "fileUploadErrorExtension" => "Un'estensione di PHP ha generato un errore durante il caricamento del file '%value%'",
    "fileUploadErrorAttack" => "Il file '%value%' è stato caricato irregolarmente. Potrebbe trattarsi di un attacco",
    "fileUploadErrorFileNotFound" => "Il file '%value%' non è stato trovato",
    "fileUploadErrorUnknown" => "Errore sconosciuto durante il caricamento del file '%value%'",

    # Zend_Validate_File_WordCount
    "fileWordCountTooMuch" => "Il file contiene troppe parole, ne sono consentite massimo '%max%' ma ne sono state contate '%count%'",
    "fileWordCountTooLess" => "Il file contiene troppe poche parole, ne sono consentite minimo '%min%' ma ne sono state contate '%count%'",
    "fileWordCountNotFound" => "Il file '%value%' non può essere trovato",

    # Zend_Validate_Float
    "floatInvalid" => "Tipo di dato non valido: il dato dev'essere di tipo float, stringa o intero.",
    "notFloat" => "'%value%' non sembra essere un dato di tipo float",

    # Zend_Validate_GreaterThan
    "notGreaterThan" => "'%value%' non è maggiore di '%min%'",

    # Zend_Validate_Hex
    "hexInvalid" => "Tipo di dato non valido, il dato dev'essere una stringa",
    "notHex" => "'%value%' non è composto solo da caratteri esadecimali",

    # Zend_Validate_Hostname
    "hostnameInvalid" => "Tipo di dato non valido, il dato dev'essere una stringa",
    "hostnameIpAddressNotAllowed" => "'%value%' sembra essere un indirizzo IP, ma gli indirizzi IP non sono consentiti",
    "hostnameUnknownTld" => "'%value%' sembra essere un hostname DNS ma il suo TLD è sconosciuto",
    "hostnameDashCharacter" => "'%value%' sembra essere un hostname DNS ma contiene un trattino in una posizione non valida",
    "hostnameInvalidHostnameSchema" => "'%value%' sembra essere un hostname DNS ma non rispetta lo schema per il TLD '%tld%'",
    "hostnameUndecipherableTld" => "'%value%' sembra essere un hostname DNS ma non è possibile estrarne il TLD",
    "hostnameInvalidHostname" => "'%value%' non sembra rispettare la struttura attesa per un hostname DNS",
    "hostnameInvalidLocalName" => "'%value%' non sembra essere un local network name valido",
    "hostnameLocalNameNotAllowed" => "'%value%' sembra essere un local network name, ma i local network names non sono consentiti",
    "hostnameCannotDecodePunycode" => "'%value%' sembra essere un hostname DNS ma la notazione punycode data non può essere decodificata",
    "hostnameInvalidUri" => "'%value%' non sembra essere un URI valido",

    # Zend_Validate_Iban
    "ibanNotSupported" => "Country Code sconosciuto nell'IBAN '%value%'",
    "ibanFalseFormat" => "'%value%' ha un formato IBAN non valido",
    "ibanCheckFailed" => "'%value%' ha fallito il controllo IBAN",

    # Zend_Validate_Identical
    "notSame" => "I due token non corrispondono",
    "missingToken" => "Non è stato passato nessun token per il confronto",

    # Zend_Validate_InArray
    "notInArray" => "'%value%' non è stato trovato nell'array",

    # Zend_Validate_Int
    "intInvalid" => "Tipo di dato non valido, il dato dev'essere una stringa o un intero",
    "notInt" => "'%value%' non sembra essere un intero",

    # Zend_Validate_Ip
    "ipInvalid" => "Tipo di dato non valido, il dato dev'essere una stringa",
    "notIpAddress" => "'%value%' non sembra essere un indirizzo IP valido",

    # Zend_Validate_Isbn
    "isbnInvalid" => "Tipo di dato non valido, il dato dev'essere una stringa o un intero",
    "isbnNoIsbn" => "'%value%' non è un numero ISBN valido",

    # Zend_Validate_LessThan
    "notLessThan" => "'%value%' non è minore di '%max%'",

    # Zend_Validate_NotEmpty
    "notEmptyInvalid" => "Tipo di dato non valido, il dato dev'essere di tipo float, stringa, array, booleano o intero",
    "isEmpty" => "Il dato è richiesto e non può essere vuoto",

    # Zend_Validate_PostCode
    "postcodeInvalid" => "Tipo di dato non valido. Il dato dev'essere una stringa o un intero",
    "postcodeNoMatch" => "'%value%' non sembra essere un codice postale",

    # Zend_Validate_Regex
    "regexInvalid" => "Tipo di dato non valido: il dato dev'essere di tipo stringa, intero o float.",
    "regexNotMatch" => "'%value%' non corrisponde al pattern '%pattern%'",
    "regexErrorous" => "Si è verificato un errore interno usando il pattern '%pattern%'",

    # Zend_Validate_Sitemap_Changefreq
    "sitemapChangefreqNotValid" => "'%value%' non è una sitemap changefreq valida",
    "sitemapChangefreqInvalid" => "Invalid type given, the value should be a string",

    # Zend_Validate_Sitemap_Lastmod
    "sitemapLastmodNotValid" => "'%value%' non è un sitemap lastmod valido",
    "sitemapLastmodInvalid" => "Tipo di dato non valido, il dato dev'essere una stringa",

    # Zend_Validate_Sitemap_Loc
    "sitemapLocNotValid" => "'%value%' non è una sitemap location valida",
    "sitemapLocInvalid" => "Tipo di dato non valido, il dato dev'essere una stringa",

    # Zend_Validate_Sitemap_Priority
    "sitemapPriorityNotValid" => "'%value%' non è una sitemap priority valida",
    "sitemapPriorityInvalid" => "Tipo di dato non valido, il dato dev'essere di tipo intero, float o una stringa numerica",

    # Zend_Validate_StringLength
    "stringLengthInvalid" => "Tipo di dato non valido, il dato dev'essere una stringa",
    "stringLengthTooShort" => "'%value%' è meno lungo di %min% caratteri",
    "stringLengthTooLong" => "'%value%' è più lungo di %max% caratteri"
);

?>