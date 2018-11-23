<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use League\HTMLToMarkdown\HtmlConverter;

class ConvertoHTMLtoMD extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'helpers:convert-html-to-markdown {inputFile} {outputFile}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Helper to convert html to markdown, mostly used to make test documentation look nice.';

    /**
     * @var HtmlConverter
     */
    private $htmlConversionService;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();

        $this->htmlConversionService = new HtmlConverter();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $inputFile = base_path($this->argument('inputFile'));
        $outputFile = base_path($this->argument('outputFile'));

        $inputDocument = new \DOMDocument();
        $inputDocument->loadHTMLFile($inputFile);

        // Extract the tests from the html document
        $xpath = new \DOMXPath($inputDocument);
        $tests = $xpath->query('/html/body/*');

        $outputDocument = '';
        foreach ($tests as $test) {
            $outputDocument .= $test->ownerDocument->saveHTML($test);
        }

        $convertedDocument = $this->htmlConversionService->convert($outputDocument);

        // convert the unicode ticks and crosses to something a bit nicer
        $convertedDocument = str_replace("\n- ", "\n\n", $convertedDocument);

        file_put_contents($outputFile, $convertedDocument);
    }
}
