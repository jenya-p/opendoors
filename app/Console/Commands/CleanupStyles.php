<?php

namespace App\Console\Commands;

use App\Models\Quiz\Question;
use Heriw\LaravelSimpleHtmlDomParser\HtmlDomParser;
use Illuminate\Console\Command;
use simplehtmldom\simple_html_dom_node;

class CleanupStyles extends Command {

    protected $signature = 'app:cleanup-styles';

    protected $description = 'Command description';

    private $stylesToRemove = [
        'font-weight', 'font-style',
        'color', 'background-color', 'font-family', 'font-size','line-height'
    ];

    private $tagsToRemove = ['strong', 'span'];

    public function handle() {
        $questions = Question::all();
        foreach ($questions as $question){
            $question->text = $this->cleanup($question->text);
            $question->text_en = $this->cleanup($question->text_en);
            if(!empty($question->options) && !empty($question->options['options']) && is_array($question->options['options'])){
                $options = $question->options;
                foreach ($options['options'] as $index => $option){
                    $options['options'][$index]['text'] = $this->cleanup($option['text']);
                    $options['options'][$index]['text_en'] = $this->cleanup($option['text_en']);
                }
                $question->options = $options;
            }
            if ($question->getDirty()){
                $question->save();
            }
        }
    }

    public function cleanup($text){
        $text = $this->step1($text);
        return $this->step2($text);
    }

    public function step1($text){

        $stylesReg = '/^(' . implode('|', $this->stylesToRemove). ')\s*:/m';
        $processNode = function(simple_html_dom_node $node, $level, $pTag, $siblingsCount) use (&$processNode, $stylesReg){
            if($node->style){
                $s = htmlspecialchars_decode(mb_strtolower($node->style));
                $s = explode(';', $s);
                $s = array_filter($s, function($itm) use ($stylesReg){
                    return !preg_match($stylesReg,ltrim($itm)) && !empty($itm);
                });
                if(empty($s)){
                    $node->removeAttribute('style');
                } else {
                    $node->style = implode(';', $s);
                }
            }

            if( (count($node->attr) == 0 || (count($node->attr) == 1 && $node->style === true)) &&
                in_array(strtolower($node->tag), $this->tagsToRemove) &&
                $siblingsCount == 1 && $level == 1
            ){
                $node->outertext = $node->innertext;
            }

            $childCount = count($node->childNodes());
            foreach ($node->childNodes() as $cNode){
                $processNode($cNode, $level+1, $node->tag, $childCount);
            }
        };

        /** @var simple_html_dom_node $html */
        $html = HtmlDomParser::str_get_html($text);
        if($html){
            $childCount = count($html->childNodes());
            foreach ($html->childNodes() as $node){
                $processNode($node, 0, '', $childCount);
            }

            return $html->innertext;
        }
        return $text;
    }

    public function step2($text){
        /** @var simple_html_dom_node $html */
        $html = HtmlDomParser::str_get_html($text);
        if($html){

            foreach ($html->childNodes() as $node){
                if($node->tag == 'p'){
                    if(count($node->childNodes()) == 0){
                        $node->innertext = preg_replace( "#(^(&nbsp;|\s)+|(&nbsp;|\s)+$)#", "", $node->innertext);
                        if(empty($node->innertext)){
                            $node->outertext = '';
                        }
                    }
                }
            }

            return $html->innertext;
        }
        return $text;
    }


}
