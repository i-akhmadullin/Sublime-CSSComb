<?php
/**
 * CSScomb
 * @version: 2.10 (build b86af6d-1203301127)
 * @author: Vyacheslav Oliyanchuk (miripiruni)
 * @web: http://csscomb.com/
 */
 
/*error_reporting(E_ALL);*/
class csscomb{

    var $sort_order = Array(),
    $code = Array(
        'original' => null, // оригинальный код, без изменений, то, что пришло на вход
        'edited' => null,   // код, который может меняться в процессе выполнения алгоритма пересортировки
        'resorted' => null,  // конечный, пересортированный CSS-код
        // TODO: избавиться от resorted
        'expressions' => null,  // если найдены expression, то эта переменная станет массивом, ячейки которого будут содержать код каждого найденного expression
        'datauri' => null,  // если найдены data uri, то эта переменная станет массивом...
        'hacks' => null,  // если найдены CSS-хаки мешающие парсить, то эта переменная станет массивом...
        'braces' => null, // если найдены комментарии содержащие { или } мешающие парсить, то эта переменная станет массивом.
        'entities' => null // если найдены entities мешающие парсить, то эта переменная станет массивом.
    ),

    /*
     * В переменной $mode лежит режим работы с CSS-кодом.
     * Возможны следующие значения:
     * css-file - только CSS-код
     * style-attribute - найден атрибут style="..."
     * properties - не найдено фигурных скобок, зато присутствуют точки с запятой и двоеточия.
     *
     */
    $mode = 'properties',


    $default_sort_order = '
[
    "position",
    "top",
    "right",
    "bottom",
    "left",
    "z-index",
    "float",
    "clear",
    "display",
    "visibility",
    "overflow",
    "overflow-x",
    "overflow-y",
    "overflow-style",
    "clip",
    "-webkit-box-sizing",
    "-moz-box-sizing",
    "box-sizing",
    "margin",
    "margin-top",
    "margin-right",
    "margin-bottom",
    "margin-left",
    "padding",
    "padding-top",
    "padding-right",
    "padding-bottom",
    "padding-left",
    "width",
    "height",
    "max-width",
    "max-height",
    "min-width",
    "min-height",
    "outline",
    "outline-width",
    "outline-style",
    "outline-color",
    "outline-offset",
    "border",
    "border-collapse",
    "border-color",
    "border-style",
    "border-width",
    "border-top",
    "border-right",
    "border-bottom",
    "border-left",
    "-webkit-border-radius",
    "-khtml-border-radius",
    "-moz-border-radius",
    "border-radius",
    "-webkit-border-top-right-radius",
    "-khtml-border-top-right-radius",
    "-moz-border-top-right-radius",
    "-moz-border-radius-topright",
    "border-top-right-radius",
    "-webkit-border-bottom-right-radius",
    "-khtml-border-bottom-right-radius",
    "-moz-border-bottom-right-radius",
    "-moz-border-radius-bottomright",
    "border-bottom-right-radius",
    "-webkit-border-bottom-left-radius",
    "-khtml-border-bottom-left-radius",
    "-moz-border-bottom-left-radius",
    "-moz-border-radius-bottomleft",
    "border-bottom-left-radius",
    "-webkit-border-top-left-radius",
    "-khtml-border-top-left-radius",
    "-moz-border-top-left-radius",
    "-moz-border-radius-topleft",
    "border-top-left-radius",
    "-webkit-border-image",
    "-moz-border-image",
    "-o-border-image",
    "border-image",
    "-webkit-border-image-source",
    "-moz-border-image-source",
    "-o-border-image-source",
    "border-image-source",
    "-webkit-border-image-slice",
    "-moz-border-image-slice",
    "-o-border-image-slice",
    "border-image-slice",
    "-webkit-border-image-width",
    "-moz-border-image-width",
    "-o-border-image-width",
    "border-image-width",
    "-webkit-border-image-outset",
    "-moz-border-image-outset",
    "-o-border-image-outset",
    "border-image-outset",
    "-webkit-border-image-repeat",
    "-moz-border-image-repeat",
    "-o-border-image-repeat",
    "border-image-repeat",
    "-webkit-border-top-image",
    "-moz-border-top-image",
    "-o-border-top-image",
    "border-top-image",
    "-webkit-border-right-image",
    "-moz-border-right-image",
    "-o-border-right-image",
    "border-right-image",
    "-webkit-border-bottom-image",
    "-moz-border-bottom-image",
    "-o-border-bottom-image",
    "border-bottom-image",
    "-webkit-border-left-image",
    "-moz-border-left-image",
    "-o-border-left-image",
    "border-left-image",
    "-webkit-border-corner-image",
    "-moz-border-corner-image",
    "-o-border-corner-image",
    "border-corner-image",
    "-webkit-border-top-left-image",
    "-moz-border-top-left-image",
    "-o-border-top-left-image",
    "border-top-left-image",
    "-webkit-border-top-right-image",
    "-moz-border-top-right-image",
    "-o-border-top-right-image",
    "border-top-right-image",
    "-webkit-border-bottom-right-image",
    "-moz-border-bottom-right-image",
    "-o-border-bottom-right-image",
    "border-bottom-right-image",
    "-webkit-border-bottom-left-image",
    "-moz-border-bottom-left-image",
    "-o-border-bottom-left-image",
    "border-bottom-left-image",
    "background",
    "filter:progid:DXImageTransform.Microsoft.AlphaImageLoader",
    "background-color",
    "background-image",
    "background-position",
    "background-size",
    "background-repeat",
    "background-attachment",
    "background-clip",
    "background-origin",
    "box-decoration-break",
    "-webkit-box-shadow",
    "-moz-box-shadow",
    "box-shadow",
    "color",
    "table-layout",
    "caption-side",
    "empty-cells",
    "list-style",
    "list-style-position",
    "list-style-type",
    "list-style-image",
    "quotes",
    "content",
    "counter-increment",
    "counter-reset",
    "vertical-align",
    "text-align",
    "text-decoration",
    "text-emphasis",
    "text-indent",
    "text-justify",
    "text-outline",
    "text-transform",
    "text-wrap",
    "text-overflow",
    "text-overflow-ellipsis",
    "text-overflow-mode",
    "text-shadow",
    "white-space",
    "word-spacing",
    "word-wrap",
    "-moz-tab-size",
    "-o-tab-size",
    "tab-size",
    "letter-spacing",
    "font",
    "font-weight",
    "font-style",
    "font-variant",
    "font-size-adjust",
    "font-stretch",
    "font-size",
    "font-family",
    "src",
    "line-height",
    "opacity",
    "-ms-filter:\'progid:DXImageTransform.Microsoft.Alpha",
    "filter:progid:DXImageTransform.Microsoft.Alpha(Opacity",
    "resize",
    "cursor",
    "nav-index",
    "nav-up",
    "nav-right",
    "nav-down",
    "nav-left",
    "-webkit-transition",
    "-moz-transition",
    "-o-transition",
    "transition",
    "-webkit-transition-delay",
    "-moz-transition-delay",
    "-o-transition-delay",
    "transition-delay",
    "-webkit-transition-timing-function",
    "-moz-transition-timing-function",
    "-o-transition-timing-function",
    "transition-timing-function",
    "-webkit-transition-duration",
    "-moz-transition-duration",
    "-o-transition-duration",
    "transition-duration",
    "-webkit-transition-property",
    "-moz-transition-property",
    "-o-transition-property",
    "transition-property",
    "-webkit-transform",
    "-moz-transform",
    "-o-transform",
    "transform",
    "-webkit-transform-origin",
    "-moz-transform-origin",
    "-o-transform-origin",
    "transform-origin",
    "unicode-bidi",
    "direction",
    "break-after",
    "break-before",
    "break-inside",
    "columns",
    "column-span",
    "column-width",
    "column-count",
    "column-fill",
    "column-gap",
    "column-rule",
    "column-rule-color",
    "column-rule-style",
    "column-rule-width",
    "page-break-before",
    "page-break-inside",
    "page-break-after",
    "orphans",
    "widows",
    "zoom",
    "max-zoom",
    "min-zoom",
    "user-zoom",
    "orientation"
]',







    $yandex_sort_order = '[
[
    "position",
    "z-index",
    "top",
    "right",
    "bottom",
    "left"
],
[
    "display",
    "visibility",
    "float",
    "clear",
    "overflow",
    "overflow-x",
    "overflow-y",
    "-ms-overflow-x",
    "-ms-overflow-y",
    "clip",
    "zoom",
    "flex-direction",
    "flex-order",
    "flex-pack",
    "flex-align"
],
[
    "-webkit-box-sizing",
    "-moz-box-sizing",
    "box-sizing",
    "width",
    "min-width",
    "max-width",
    "height",
    "min-height",
    "max-height",
    "margin",
    "margin-top",
    "margin-right",
    "margin-bottom",
    "margin-left",
    "padding",
    "padding-top",
    "padding-right",
    "padding-bottom",
    "padding-left"
],
[
    "table-layout",
    "empty-cells",
    "caption-side",
    "border-spacing",
    "border-collapse",
    "list-style",
    "list-style-position",
    "list-style-type",
    "list-style-image"
],
[
    "content",
    "quotes",
    "counter-reset",
    "counter-increment",
    "resize",
    "cursor",
    "nav-index",
    "nav-up",
    "nav-right",
    "nav-down",
    "nav-left",
    "-webkit-transition",
    "-moz-transition",
    "-ms-transition",
    "-o-transition",
    "transition",
    "-webkit-transition-delay",
    "-moz-transition-delay",
    "-ms-transition-delay",
    "-o-transition-delay",
    "transition-delay",
    "-webkit-transition-timing-function",
    "-moz-transition-timing-function",
    "-ms-transition-timing-function",
    "-o-transition-timing-function",
    "transition-timing-function",
    "-webkit-transition-duration",
    "-moz-transition-duration",
    "-ms-transition-duration",
    "-o-transition-duration",
    "transition-duration",
    "-webkit-transition-property",
    "-moz-transition-property",
    "-ms-transition-property",
    "-o-transition-property",
    "transition-property",
    "-webkit-transform",
    "-moz-transform",
    "-ms-transform",
    "-o-transform",
    "transform",
    "-webkit-transform-origin",
    "-moz-transform-origin",
    "-ms-transform-origin",
    "-o-transform-origin",
    "transform-origin",
    "-webkit-animation",
    "-moz-animation",
    "-ms-animation",
    "-o-animation",
    "animation",
    "-webkit-animation-name",
    "-moz-animation-name",
    "-ms-animation-name",
    "-o-animation-name",
    "animation-name",
    "-webkit-animation-duration",
    "-moz-animation-duration",
    "-ms-animation-duration",
    "-o-animation-duration",
    "animation-duration",
    "-webkit-animation-play-state",
    "-moz-animation-play-state",
    "-ms-animation-play-state",
    "-o-animation-play-state",
    "animation-play-state",
    "-webkit-animation-timing-function",
    "-moz-animation-timing-function",
    "-ms-animation-timing-function",
    "-o-animation-timing-function",
    "animation-timing-function",
    "-webkit-animation-delay",
    "-moz-animation-delay",
    "-ms-animation-delay",
    "-o-animation-delay",
    "animation-delay",
    "-webkit-animation-iteration-count",
    "-moz-animation-iteration-count",
    "-ms-animation-iteration-count",
    "-o-animation-iteration-count",
    "animation-iteration-count",
    "-webkit-animation-iteration-count",
    "-moz-animation-iteration-count",
    "-ms-animation-iteration-count",
    "-o-animation-iteration-count",
    "animation-iteration-count",
    "-webkit-animation-direction",
    "-moz-animation-direction",
    "-ms-animation-direction",
    "-o-animation-direction",
    "animation-direction",
    "text-align",
    "text-align-last",
    "-ms-text-align-last",
    "text-align-last",
    "vertical-align",
    "white-space",
    "text-decoration",
    "text-emphasis",
    "text-emphasis-color",
    "text-emphasis-style",
    "text-emphasis-position",
    "text-indent",
    "-ms-text-justify",
    "text-justify",
    "text-transform",
    "letter-spacing",
    "word-spacing",
    "-ms-writing-mode",
    "text-outline",
    "text-transform",
    "text-wrap",
    "text-overflow",
    "-ms-text-overflow",
    "text-overflow-ellipsis",
    "text-overflow-mode",
    "-ms-word-wrap",
    "word-wrap",
    "word-break",
    "-ms-word-break",
    "-moz-tab-size",
    "-o-tab-size",
    "tab-size",
    "-webkit-hyphens",
    "-moz-hyphens",
    "hyphens"
],
[
    "opacity",
    "filter:progid:DXImageTransform.Microsoft.Alpha(Opacity",
    "-ms-filter:\'progid:DXImageTransform.Microsoft.Alpha",
    "-ms-interpolation-mode",
    "color",
    "border",
    "border-collapse",
    "border-width",
    "border-style",
    "border-color",
    "border-top",
    "border-top-width",
    "border-top-style",
    "border-top-color",
    "border-right",
    "border-right-width",
    "border-right-style",
    "border-right-color",
    "border-bottom",
    "border-bottom-width",
    "border-bottom-style",
    "border-bottom-color",
    "border-left",
    "border-left-width",
    "border-left-style",
    "border-left-color",
    "-webkit-border-radius",
    "-moz-border-radius",
    "border-radius",
    "-webkit-border-top-right-radius",
    "-moz-border-top-right-radius",
    "border-top-right-radius",
    "-webkit-border-bottom-right-radius",
    "-moz-border-bottom-right-radius",
    "border-bottom-right-radius",
    "-webkit-border-bottom-left-radius",
    "-moz-border-bottom-left-radius",
    "border-bottom-left-radius",
    "-webkit-border-top-left-radius",
    "-moz-border-top-left-radius",
    "border-top-left-radius",
    "-webkit-border-image",
    "-moz-border-image",
    "-o-border-image",
    "border-image",
    "-webkit-border-image-source",
    "-moz-border-image-source",
    "-o-border-image-source",
    "border-image-source",
    "-webkit-border-image-slice",
    "-moz-border-image-slice",
    "-o-border-image-slice",
    "border-image-slice",
    "-webkit-border-image-width",
    "-moz-border-image-width",
    "-o-border-image-width",
    "border-image-width",
    "-webkit-border-image-outset",
    "-moz-border-image-outset",
    "-o-border-image-outset",
    "border-image-outset",
    "-webkit-border-image-repeat",
    "-moz-border-image-repeat",
    "-o-border-image-repeat",
    "border-image-repeat",
    "outline",
    "outline-width",
    "outline-style",
    "outline-color",
    "outline-offset",
    "background",
    "filter:progid:DXImageTransform.Microsoft.AlphaImageLoader",
    "background-color",
    "background-image",
    "background-repeat",
    "background-attachment",
    "background-position",
    "background-position-x",
    "-ms-background-position-x",
    "background-position-y",
    "-ms-background-position-y",
    "background-clip",
    "background-origin",
    "background-size",
    "box-decoration-break",
    "-webkit-box-shadow",
    "-moz-box-shadow",
    "box-shadow",
    "-webkit-box-shadow",
    "-moz-box-shadow",
    "box-shadow",
    "-webkit-box-shadow",
    "-moz-box-shadow",
    "box-shadow",
    "-webkit-box-shadow",
    "-moz-box-shadow",
    "box-shadow",
    "filter:progid:DXImageTransform.Microsoft.gradient",
    "-ms-filter:\'progid:DXImageTransform.Microsoft.gradient",
    "text-shadow"
],
[
    "font",
    "font-family",
    "font-size",
    "font-weight",
    "font-style",
    "font-variant",
    "font-size-adjust",
    "font-stretch",
    "font-effect",
    "font-emphasize",
    "font-emphasize-position",
    "font-emphasize-style",
    "font-smooth",
    "line-height"
]
]';

    /**
     * Функция конструктор
     * @param css {string}
     * @param echo {boolean}
     * @custon_sort_order {string|JSON}
     *
     * @TODO: https://github.com/miripiruni/CSScomb/issues/21
     *
     */
    function csscomb($css = '', $echo = false, $custom_sort_order = null){
        if($echo===0 or $echo===false){
            $this->output = false;
        }

        if($css != ''){

            $this->code['original'] = $css;
            $this->code['edited'] = $css;

            $this->set_mode();
            $this->set_sort_order($custom_sort_order); // 1 задаем порядок сортировки

            $this->preprocess();        // 2 препроцессинг
            $this->parse_rules();       // 3,4,5 парсим на части по скобкам
            $this->postprocess();       // 6 постпроцессинг

            return $this->end_of_process();
        }
    }





    /**
     * Функция сетит $this->sort_order
     *
     * @param json_array {string/JSON}
     *
     */
    function set_sort_order($json_array = null){
        if($json_array != null){
            $custom_sort_order = json_decode($json_array);
            if(is_array($custom_sort_order) AND count($custom_sort_order)>0){
                $this->sort_order = $custom_sort_order;
            }
            else {
                $this->sort_order = json_decode($this->default_sort_order);
            }

        }
        else {
            $this->sort_order = json_decode($this->default_sort_order);
        }

        if($json_array === 'yandex'){
            $this->sort_order = json_decode($this->yandex_sort_order);
            //switch(json_last_error()){
                //case JSON_ERROR_DEPTH:
                    //echo 'JSON parse error: Достигнута максимальная глубина стека';
                //break;
                //case JSON_ERROR_STATE_MISMATCH:
                    //echo 'JSON parse error: Некорректные разряды или не совпадение режимов';
                //break;
                //case JSON_ERROR_CTRL_CHAR:
                    //echo 'JSON parse error: Некорректный управляющий символ';
                //break;
                //case JSON_ERROR_SYNTAX:
                    //echo 'JSON parse error: Синтаксическая ошибка, не корректный JSON';
                //break;
            //}
        }
    }



    /**
     * Функция сетит $this->mode
     *
     * @TODO: а если и тег <style> и несколько style="..." в HTML?
     *        https://github.com/miripiruni/CSScomb/issues/9
     */
    function set_mode(){
        if(strpos($this->code['original'], '{')){ // если есть фигурные скобки
                $this->mode = 'css-file';
        }
        else{ // если нет фигурных скобок
            if(strpos($this->code['original'], "style='") OR strpos($this->code['original'], 'style="')){ // если есть атрибут
                $this->mode = 'style-attribute';
            }
            // если есть двоеточия и точки с запятой то это набор свойств
            else if(strpos($this->code['original'], ':') AND strpos($this->code['original'], ';')){
                $this->mode = 'properties';
            }
        }
    }


    /**
     * @TODO: почему нигде не используется? Убрать?
     */
    function get_sort_order($order_name = null){
        $order = '';
        if($order_name !== null){
            if($order_name == 'zen'){
                $this->set_sort_order($this->default_sort_order);
                foreach($this->sort_order as $k=>$prop){
                    $order .= $prop."
";
                }
            }

            if($order_name == 'yandex'){
                $this->set_sort_order($this->yandex_sort_order);
                foreach($this->sort_order as $group){
                    foreach($group as $prop){
                        $order .= $prop."
";
                    }
                    $order .= "
";
                }
                $order = trim($order);
            }
        }
        return $order;

    }





    function preprocess(){
        // 1. экранирование хаков, которые мешают парсить
        if(strpos($this->code['edited'], '"\\"}\\""')){ // разбираемся со страшным хаком "\"}\""
            $i = 0;
            $this->code['hacks'] = array();
            while(strpos($this->code['edited'], '"\\"}\\""')):
                $this->code['hacks'][] = '"\\"}\\""';
                $this->code['edited'] = str_replace('"\\"}\\""', 'hack'.$i++.'__', $this->code['edited']);
            endwhile;
        }

        // 2. expressions
        if(strpos($this->code['edited'], 'expression(')){ // разбираемся с expression если они присутствуют
            $i = 0;
            $this->code['expressions'] = array();
            while(strpos($this->code['edited'], 'expression(')):
                preg_match_all('#(.*)expression\((.*)\)#ism', $this->code['edited'], $match, PREG_SET_ORDER); // вылавливаем expression
                $this->code['expressions'][] = $match[0][2]; // собираем значения expression(...)
                $this->code['edited'] = str_replace('expression('.$match[0][2].')', 'exp'.$i++.'__', $this->code['edited']);
            endwhile;
        }

        // 3. data uri
        if(strpos($this->code['edited'], ';base64,')){
            $i = 0;
            $this->code['datauri'] = array();
            while(strpos($this->code['edited'], ';base64,')):
                preg_match_all('#(url\(["\']?data:.[^\)]*["\']?\))#ism', $this->code['edited'], $match, PREG_SET_ORDER); // вылавливаем data uri
                $this->code['datauri'][] = $match[0][1]; // собираем значения
                $this->code['edited'] = str_replace($match[0][1], 'datauri'.$i++.'__', $this->code['edited']);
            endwhile;
        }

        // 4. Всякое разное...
        $this->code['edited'] = str_replace('{}','{ }', $this->code['edited']); // закрываем сложности парсинга {}
        $this->code['edited'] = preg_replace('/(.*?[^\s])(\s*?})/','$1;$2', $this->code['edited']); // закрываем сложности с отсутствующей последней ; перед }


        // 5. Комментарии
        if(preg_match_all('@
            (
            \s*
            /\*
            .*?[^\*/]
            \*/
            (\s/\*\*/)?
            )
            @ismx', $this->code['edited'], $test)){

            // 1. Текстовый комментарий не содержащий свойств: всё, где нет ни :, ни ;, ни {|}, но есть какие-то буквы/цифры
                // Ничего не делаем.

            // 2. Одно свойство: есть : и ; но после ; ничего нет кроме \s.
                // заменяем на commented__border: 1px solid red;

            // 3. Закомментировано одно или несколько свойств: повторяющийся паттерн *:*; \s*?
            if(preg_match_all('#
                (\s*)
                /\*
                (.*?[^\*/])
                \*+/
                (\ {0,1}/\*\*/)?
                #ismx', $this->code['edited'], $comments)){

                $new_comments = Array();
                $old_comments = $comments[0];

                foreach($comments[2] as $key=>$comment){
                    if( // если комментарий содержит ; и :
                        strpos($comment, ':') !== FALSE AND
                        strpos($comment, ';') !== FALSE

                    ){
                        preg_match_all('#
                        (\s*)
                        (
                            .+?[^;]
                            ;
                        )
                        #ismx', $comment, $properties);

                        $new_comment = '';
                        foreach($properties[2] as $property){
                            $new_comment .= $comments[1][$key]."commented__".$property;
                        }
                        $new_comments[] = $new_comment;
                    }
                    else{ // если нет : или ;, то считаем что это текстовый комментарий и копируем его в том виде, в каком он был.
                        $new_comments[] = $comments[0][$key];
                    }


                }

                foreach($old_comments as $key => $old_comment){
                    $this->code['edited'] = str_replace($old_comments[$key], $new_comments[$key], $this->code['edited']);
                }
            }

            // 4. Текст и свойства вперемешку

            // 5. Пустой комментарий: если сделать трим то ничего не останется
                // Ничего не делаем.

            // 6. Обрывки закомментированных деклараций: присутствует { или }
            if(preg_match_all('#
                    \s*?
                    /\*
                    (
                        .*?[^\*/]
                    )*?
                    \*+/
                #ismx', $this->code['edited'], $comments)){

                $new_comments = Array();
                $old_comments = $comments[0];

                foreach($comments[0] as $key=>$comment){
                    if(strpos($comment, '}') !== FALSE OR strpos($comment, '{') !== FALSE){
                        $new_comment = '';
                        if(strpos($comment, '}') !== FALSE){ $new_comment .= '}'; }
                        $new_comment .= "brace__".$key;
                        if(strpos($comment, '{') !== FALSE){ $new_comment .= '{'; }
                        $new_comments[$key] = $new_comment;
                        $this->code['braces'][$key] = $comment;
                    }
                }

                foreach($new_comments as $key => $new_comment){
                    if(strlen($new_comment) > 0){
                        $this->code['edited'] = str_replace($old_comments[$key], $new_comment, $this->code['edited']);
                    }
                }
            }
        }

        // 7. Entities
        if(preg_match_all('#
            \&
            \#?
            [\d\w]*?[^;]
            \;
            #ismx', $this->code['edited'], $entities)){

            $this->code['entities'] = array();

            foreach($entities[0] as $key => $val){
                $this->code['entities'][$key] = $val;
                $this->code['edited'] = str_replace($val, 'entity__'.$key, $this->code['edited']);
            }

        }
    }


    /**
     * Зависит от $this->mode
     * Из $this->code['edited'] получает массив разбитый по }
     *
     */
    function parse_rules(){

        if($this->mode == 'css-file'){

            // отделяем все, что после последней } если там что-то есть, конечно :)
            preg_match('@

                (
                    .*[^}]
                    }
                )
                (.*)

            @ismx', $this->code['edited'], $matches);

            $code_without_end = $matches[1];
            $end_of_code = $matches[2];

            /**
             * Разбиваем CSS-код на части по { или }
             * Это позволяет поддерживать LESS CSS, @media, @-webkit-keyframes и любые другие конструкции
             * использующие вложенные фигурные скобки
             */
            preg_match_all('@

                .*?[^}{]  # находим код между соседними скобками {|}
                \s*?
                [}{]


            @ismx', $code_without_end, $matches);

            $rules = $matches[0]; // CSS-код разрезанный по фигурным скобкам

            //TODO: вынести вызов parse_prop в csscomb(), сделать чтобы parse_rules возвращала результат своей работы в виде $rules
            foreach($rules as $key=>$val){
                $rules[$key] = $this->parse_properties($val);  // 4 парсим и сортируем каждую часть
            }

            $this->code['resorted'] = implode($this->array_implode($rules)).$end_of_code;            // 5 склеиваем части
        }


        if($this->mode == 'style-attribute'){

            $this->code['resorted'] = $this->code['edited'];

            preg_match_all('@

                .*?[^"\']

                style=
                ["\']
                (.*?)
                ["\']


            @ismx', $this->code['edited'], $matches);

            $properties = $matches[1];

            //TODO: вынести вызов parse_prop в csscomb(), сделать чтобы parse_rules возвращала результат своей работы в виде $rules
            foreach($properties as $props){
                $r = $this->parse_properties($props);
                $this->code['resorted'] = str_replace($props, $r, $this->code['resorted']);
            }

        }



        if($this->mode == 'properties'){
			preg_match('@\s*?.*?[^;\s];(\s)@ismx', $this->code['edited'], $matches);
            $this->code['edited'] = $matches[1].$this->code['edited'];
            //TODO: Не использовать parse_prop здесь, а делать вызов в csscomb. Пусть функции общаются между собой через csscomb
            $rules[0] = ltrim($this->parse_properties($this->code['edited']), "\r\n");
            $this->code['resorted'] = implode($this->array_implode($rules)).$end_of_code;
        }
    }


    /**
     * Сильно зависит от $this->mode
     *
     * парсит CSS-декларации из строки
     * @param css {string}
     *
     */
    function parse_properties($css = ''){
        if($this->mode == 'css-file'){
            // отделяем фигурную скобку
            $matches = null;
            preg_match('@

                ^
                (.*?)
                (
                    #(\s*/\*.*\*/;)*?
                    \s*?
                    }
                )

            @ismx', $css, $matches);

            $all = null;
            preg_match_all('@
                ^
                (
                    \s*
                    /\*.*\*/
                )
                ;
                (
                    \s*
                    }
                )
            @ismx', $css, $all);

            if(count($all[0]) > 0 and $all[0][0] != null and $all[0][0] == $css){ // Если в этом участке кода ничего нет кроме одиногоко /* ... */ и закрывающей }
                $all[0][0] = '';
                return $all[1][0].$all[2][0];
            }

            if(sizeof($matches)>0 and strlen($matches[1]) > 0){ // если есть и свойства и скобка и хотя бы одно :
                $properties = $matches[1];
                $brace = $matches[2];

                if(is_array($this->sort_order[0])){ // Если порядок сортировки разбит на группы свойств
                    /**
                     * Если CSS-свойства уже были разделены на группы пустой 
                     * строкой, то нужно поудалять это разделение, чтобы сделать 
                     * новое.
                     */
                    $properties = preg_replace('/
                        \r?\n
                        \ *?
                        \r?\n
                        /ismx', "\n", $properties);

                }

                /* отделяем первый комментарий, который находится на той же строке, где и была скобка */
                $matches = null;
                $first_spaces = $first_comment = '';
                preg_match('@

                    ^
                    (.*?)
                    (\s*?)
                    (/\* .* \*/)
                    (.*)
                    $

                    @ismx', $properties, $matches);

                if(
                    count($matches)==5 and              // все распарсилось как надо
                    strlen($matches[1]) === 0 and       // комментарий действительно идет первым
                    strpos($matches[2], "\n") !== 0     // перед комментарием нет переноса строки, следовательно предпологаем, что он относится к скобке с селектором
                ){
                    $first_spaces = $matches[2];
                    $first_comment = $matches[3];
                    $properties = $matches[4];
                }

                $matches = null;
                preg_match_all('@

                    \s*
                    (
                        .[^:]*
                        [:>]
                        .[^;]*
                        ;
                        (               # На этой же строке (после ;) может быть комментарий. Он тоже пригодится.
                            \s*
                            /\*
                            .*?[^\*/]
                            \*/
                        )
                        ?
                        (\s{0,1}/\*\*/)?

                    )

                    @ismx', $properties, $matches);

                $props = $matches[0];

                $props = $this->resort_properties($props);
                $props = $first_spaces.$first_comment.implode($props).$brace;

            }
            else $props = $css;
        }

        if($this->mode == 'properties' OR $this->mode == 'style-attribute'){
            preg_match_all('@

                    \s*
                    (
                        .[^:]*          # все что угодно, но не :
                        :
                        .[^;]*          # все что угодно, но не ;
                        ;
                        (               # На этой же строке (после ;) может быть комментарий. Он тоже пригодится.
                            \s*
                            /\* .* \*/
                        )
                        *?
                    )

                    @ismx', $css, $matches);

            $props = $matches[0];

            if(sizeof($props)>0){ // если есть и свойства и скобка и хотя бы одно :
                $props = $this->resort_properties($props);
                $props = implode($props);
            }
            else{
                $props = $css;
            }
        }

        return $props;
    }


    /**
     * Функция выполняет сортировку свойств
     *
     */
    function resort_properties($prop){
        $resorted = $undefined = array();

        foreach($prop as $k=>$val){
            $index = null; // Дефолтное значение индекса порядка для свойства. Если свойство не знакомо, то index так и останется null.
            preg_match_all('@\s*?(.*?[^:]:).*@ism', $val, $matches, PREG_SET_ORDER);
            $property = trim($matches[0][1]);

            if(is_array($this->sort_order[0])){ // Если порядок сортировки разбит на группы свойств

                foreach($this->sort_order as $pos=>$key){ // для каждой группы свойств
                    foreach($this->sort_order[$pos] as $p=>$k){ // для каждого свойства
                        if(
                            /**
                             * Пробел в начале добавляется специально, чтобы избежать совпадений по вхождению
                             * одной строки в другую. Например: top не должно совпадать с border-top
                             */
                            strpos(' '.trim($property),' '.$k.':')!==FALSE OR
                            strpos(' '.trim($property),' commented__'.$k.':')!==FALSE

                        ){
                            $through_number = $this->get_through_number($k); // определяем "сквозной" порядковый номер
                            if($through_number!==false) $index = $through_number;
                        }
                    }
                }

            }
            else{
                foreach($this->sort_order as $pos=>$key){
                    if(
                        // пробел в начале добавляется специально.
                        strpos(' '.trim($property), ' '.$key.':')!==FALSE OR
                        strpos(' '.trim($property), ' commented__'.$key.':')!==FALSE
                    ){
                        $index = $pos;
                    }
                }

            }

            if($index === null OR strpos($val, 'exp')){
                $undefined[] = $val;
            }
            else{
                /*
                   Добавляет к уже существующей записи с определенном порядковым номером еще одну запись с таким же порядковым номером
                   либо создает новую запись если с таким порядковым номером ничего еще не встречалось
                */
                if(isset($resorted[$index])) $resorted[$index] .= $val;
                else $resorted[$index] = $val;
            }
        }
        ksort($resorted);

        if(is_array($this->sort_order[0]) AND count($resorted)>0){ // Если свойства разделены на группы
            $resorted = $this->separate_property_group($resorted);
        }

        if(is_array($this->sort_order[0]) AND count($undefined)>0){
            $undefined[0] = "\n".$undefined[0];
        }

        $resorted = array_merge($resorted, $undefined); // добавляем в конец нераспознанное

        return $resorted;
    }


    /**
     * Склеивает многомерный массив в строку
     */
    function array_implode($arrays, &$target = array()){
        foreach ($arrays as $item){
            if (is_array($item)){
                $this->array_implode($item, $target);
            }
            else{
                $target[] = $item;
            }
        }
        return $target;
    }


    /**
     * Постпроцесс, убираем все подстановки и возвращаем на место всё, что мешало сортировке
     */
    function postprocess(){
        // 1. экранирование хаков с использованием ключевых символов например voice-family: "\"}\"";
        if(is_array($this->code['hacks'])){ // если были обнаружены и вырезаны хаки
            foreach($this->code['hacks'] as $key=>$val){
                if(strpos($this->code['resorted'], 'hack'.$key.'__')) $this->code['resorted'] = str_replace('hack'.$key.'__', $val, $this->code['resorted']); // заменяем значение expression обратно
            }
        }

        // 2. expressions
        if(is_array($this->code['expressions'])){ // если были обнаружены и вырезаны expression
            foreach($this->code['expressions'] as $key=>$val){
                if(strpos($this->code['resorted'], 'exp'.$key.'__')) $this->code['resorted'] = str_replace('exp'.$key.'__', 'expression('.$val.')', $this->code['resorted']); // заменяем значение expression обратно
            }
        }

        // 3. datauri
        if(is_array($this->code['datauri'])){ // если были обнаружены и вырезаны data uri
            foreach($this->code['datauri'] as $key=>$val){
                if(strpos($this->code['resorted'], 'datauri'.$key.'__')) $this->code['resorted'] = str_replace('datauri'.$key.'__', $val, $this->code['resorted']); // заменяем значение expression обратно
            }
        }

        // 4. Удаляем искусственно созданные 'commented__'
        while(strpos($this->code['resorted'], 'commented__') !== FALSE){
            $this->code['resorted'] = preg_replace(
                '#
                    commented__
                    (.*?[^:]
                    :
                    .*?[^;]
                    ;)
                #ismx',
                '/* $1 */',
                $this->code['resorted']
            );
        }

        // 5. Удаляем искусственно созданные 'brace__'
        if(is_array($this->code['braces'])){ // если были обнаружены и вырезаны хаки
            foreach($this->code['braces'] as $key => $val){
                if(strpos($this->code['resorted'], 'brace__'.$key.'{') !== FALSE) {
                    $this->code['resorted'] = str_replace('brace__'.$key.'{', $val, $this->code['resorted']);
                }
                if(strpos($this->code['resorted'], '}brace__'.$key) !== FALSE) {
                    $this->code['resorted'] = str_replace('}brace__'.$key, $val, $this->code['resorted']);
                }
            }
        }


        // 7. Entities
        if(is_array($this->code['entities'])){ // если были обнаружены и вырезаны entities
            foreach($this->code['entities'] as $key => $val){
                if(strpos($this->code['resorted'], 'entity__'.$key) !== FALSE) {
                    $this->code['resorted'] = str_replace('entity__'.$key, $val, $this->code['resorted']);
                }
            }
        }

    }



    function end_of_process(){
        if($this->code['edited']!='' AND $this->output!==false){
            echo '<style>
    body{margin:0;}
    .diff{
        width:100%;
        height:400px;
        overflow:auto;
        }
        .diff textarea{
            width:50%;
            height:9999px;
            padding:0;
            margin:0;
            border:0;
            background:#f5f5f5;
            }
</style>
<div class="diff">
<textarea name="in" id="in" cols="30" rows="10">'.$this->code['original'].'</textarea><textarea name="out" id="out" cols="30" rows="10">'.$this->code['resorted'].'</textarea>
</div>';
        }

        if($this->output===false) return $this->code['resorted'];
    }


    function log($before, $after){
        echo '
        <style>pre{word-wrap: break-word;}</style>
        <div class="php"><pre class="php"><code>'.$before.'';
        echo '<br>';
        echo '<br>';
        echo ''.var_dump($after).'</code></pre></div>';
    }



    /**
     * Возвращает сквозной прядковый номер элемента двумерного массива так, как если бы этот массив был одномерным
     * @param  {string}
     * @return {bool|int}
     */
    private function get_through_number($value){
        $i = 0;
        foreach($this->sort_order as $property_group){
            foreach($property_group as $key=>$val){
                if($val==$value) return $i;
                else $i++;
            }
        }
        return false;
    }

    /**
     * Разделяет свойства на группы пустой строкой
     * Внимание: вызывать только когда есть разделение на группы, иначе вернет входной массив без изменений
     * @param  {array}
     * @return {array}
     */
    private function separate_property_group($properties){
        if(is_array($this->sort_order[0])){ // Если в настройках нет разбиения на группы, то выходим входной массив без изменений
            foreach($properties as $key=>$property){
                $array = explode(':', $property);
                $prop_name[$key] = trim($array[0]);
            }
            foreach($this->sort_order as $group_num=>$property_group){ // Перебираем группы свойств
                $intersect = array_intersect($prop_name, $property_group);
                if(count($intersect)>0){
                    $num = array_keys($intersect);
                    $last_key = null;
                    foreach($num as $n)	$last_key = $n;
                    if($properties[$last_key] != end($properties)) $properties[$last_key] = $properties[$last_key]."\n";
                }
            }
        }
        return $properties;
    }

}