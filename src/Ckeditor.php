<?php

class Ckeditor {

    /**
     * Caminho padrão, de onde fica localizado os arquivos JS e CSS.
     * @var string
     */
    private $path = 'src/ckeditor/';

    /**
     * Define a tag pardrão para iniciar a edição
     * @var string
     */
    private $tag = 'textarea';

    /**
     * Se o modo de edição estará disponível ou não.
     * @var bool
     */
    private $editable = false;

    /**
     * Deve conter o componentes que serão instanciados.
     * @var array
     */
    private $components = array();

    /**
     * Arquivos CSS
     * @var array
     */
    private $css = array();

    /**
     * Arquivos JS
     * @var array
     */
    private $js = array(
        'ckeditor.js',
        'lang/pt-br.js'
    );

    /**
     * Define um toolbar padrão
     * @var array
     */
    private $toolbar = array(
        'basicstyles' => ['Bold', 'Italic', 'Strike', 'RemoveFormat'],
        'paragraph' => ['NumberedList', 'BulletedList', 'Outdent', 'Indent', 'Blockquote', '-', 'JustifyLeft', 'JustifyCenter', 'JustifyRight', 'JustifyBlock'],
        'links' => ['Link', 'Unlink', 'TextColor', 'BGColor'],
        'editing' => ['Scayt', 'Source']
    );

    private $settings = array(
        'enterMode' => 'CKEDITOR.ENTER_BR',
        'language' => '"pt-BR"',
        'toolbar',
    );

    /**
     * Modelo do jQuery: document.ready()
     * @var string
     */
    private $document_ready = '<script type="text/javascript"> $(document).ready(function () { %script% }); </script>';

    /**
     * Instaciando o ckeditor no modo "article"
     * @var string
     */
    private $article = 'CKEDITOR.replace("%component%"%settings%);';

    /**
     * Instaciando o ckeditor no modo "inline"
     * @var string
     */
    private $inline = 'CKEDITOR.disableAutoInline = true; CKEDITOR.inline("%component%"%settings%);';

    /**
     * Modelo de exibição do componente de edição
     * @var string
     */
    private $input = '<%tag% id="%component%" %attributes%> %content% </%tag%>';

    /**
     * Script que faz a consulta ao arquivo php, onde deverá conter a lógica da programação
     * @var string
     */
    private $ajaxResponse = '
        $("#%button-component%").click(function(){ 
            %vars% 
            $.post("%saveroute%", { %parameters% })
            .done(function(data) { 
                $("#%response-status%").html(data); 
            }); 
        });
    ';

    /**
     * Modelo de exibição do botão de ação e a div que deve exibir os status das ações
     * @var string
     */
    private $templateActions = '
        <div class="row">
            <div class="col-md-12 text-center">
                <div id="%response-status%"></div>
                <button id="%button-component%" class="btn btn-info">Salvar Alterações</button>
            </div>
        </div>
    ';

    /**
     * O usuário pode alterar o caminho padrão, de onde se localiza os arquivos de JS e CSS
     * @param string $path
     */
    public function setPath(string $path) {
        $this->path = $path;
    }

    /**
     * O usuário poderá habilitar e desativar o modo de edição
     * @param $editable
     */
    public function setEditable(bool $editable) {
        $this->editable = $editable;
    }

    /**
     * O usuário poderá mudar as configurações padrão do CKEDITOR
     * @param array $settings
     */
    public function setSettings($settings) {
        $this->settings = $settings;
    }

    /**
     * @param string $tag
     */
    public function setTag($tag) {
        $this->tag = $tag;
    }

    /**
     * O usuário pode alterar o toolbar padrão do CKEDITOR
     * @param array $toolbar
     */
    public function setToolbar(array $toolbar) {
        $this->toolbar = $toolbar;
    }

    /**
     * É chamado os arquivos CSS
     * @return array
     */
    public function getCss() {
        if($this->editable){
            foreach ($this->css as $css) {
                $this->decode('<link rel="stylesheet" type="text/css" href="' . $this->path . $css . '">');
            }
        }
    }

    /**
     * É chamado os arquivos JS
     * @return array
     */
    public function getJs() {
        if($this->editable){
            foreach ($this->js as $js) {
                $this->decode('<script type="text/javascript" src="' . $this->path . $js . '"></script>');
            }
        }
    }

    public function article($component, $content, $settings = array(), $attributes = array()){
        $this->createEditor($this->article, $content, $component, $settings, $attributes);
    }

    public function inline($component, $content, $settings = array(), $attributes = array()){
        $attributes['contenteditable'] = 'true';
        $this->createEditor($this->inline, $content, $component, $settings, $attributes);
    }

    private function createEditor($script, $content, $component, $settings = array(), $attributes = array()){

        if($this->editable) {

            $this->decode($this->makeInputEditable($component, $content, $attributes));
            $this->decode($this->makeContent($script, $component, $settings));
            $this->components[] = $component;

            return;
        }

        $this->decode($content);
    }

    private function makeInputEditable($component, $content, $attributes = array()){
        $input = str_replace('%tag%', $this->tag, $this->input);
        $input = str_replace('%component%', $component, $input);
        $input = str_replace('%content%', $content, $input);
        $input = str_replace('%attributes%', $this->getAttributes($attributes), $input);
        $this->setTag('textarea');
        return $input;
    }

    private function makeContent($script, $component, $settings = array()){
        $content = str_replace('%script%', $script, $this->document_ready);
        $content = str_replace('%component%', $component, $content);
        return str_replace('%settings%', $this->getSettings($settings), $content);
    }

    private function getAttributes($attributes){
        $str = '';
        foreach ($attributes as $key => $item){
            $str .= is_numeric($key) ? $key . ' ' : $key . '="' . $item . '"';
        }
        return $str;
    }

    private function getSettings(array $settings){
        $str = '';
        $mixed = array();

        $defaultSettings = $this->settings;
        $defaultToolbar = $this->toolbar;

        if(! empty($settings)){
            $this->settings = $settings;
        }

        if( isset($settings['toolbar']) ){
            $this->setToolbar($settings['toolbar']);
            $this->settings[] = 'toolbar';
        }

        if( in_array('toolbar', $this->settings) ){
            $this->settings['toolbar'] = $this->getToolbar();
        }

        foreach ($this->settings as $key => $value) {
            if(! is_numeric($key) ){
                $mixed[] = $key . ': ' . $value;
            }
        }

        $this->setSettings($defaultSettings);
        $this->setToolbar($defaultToolbar);

        return empty($mixed) ? '' : ', {' . implode(',', $mixed) . '}' ;
    }

    /**
     * @return array
     */
    private function getToolbar() {
        $str = array();

        foreach ($this->toolbar as $key => $items) {
            $str[] = '{name:"' . $key . '", items: [' . $this->getItems($items) . '] }';
        }

        return '[' . implode(',', $str) . ']';
    }

    private function getItems($items){
        $mixed = array();

        foreach ($items as $item) {
            $mixed[] = '"' . $item . '"';
        }

        return implode(',', $mixed);
    }

    public function action($route, $attributes = array()) {
        $button = uniqid();
        $response = uniqid();

        $this->decode($this->makeAction($button, $response, $attributes));
        $this->decode($this->makeScript($route, $button, $response, $this->makeComponents()));
    }

    private function makeScript($route, $button, $response, $mixed = array()){
        $script = str_replace('%script%', $this->ajaxResponse, $this->document_ready);
        $script = str_replace('%vars%', $mixed['vars'], $script);
        $script = str_replace('%parameters%', $mixed['parameters'], $script);
        $script = str_replace('%saveroute%', $route, $script);
        $script = str_replace('%response-status%', $response, $script);
        return str_replace('%button-component%', $button, $script);
    }

    private function makeAction($button, $response, $attributes = array()){
        $template = str_replace('%response-status%', $response, $this->templateActions);
        return str_replace('%button-component%', $button, $template);
    }

    private function makeComponents(){
        $count = 0;
        $mixed = array('vars' => array(), 'parameters' => array());

        foreach ($this->components as $component) {
            $mixed['vars'][] = 'var variable' . $count . ' = CKEDITOR.instances.' . $component . '.getData();';
            $mixed['parameters'][] = '"' . $component . '" : variable' . $count;
            $count++;
        }

        $mixed['vars'] = implode(' ', $mixed['vars']);
        $mixed['parameters'] = implode(', ', $mixed['parameters']);

        $this->components = array();
        return $mixed;
    }

    /**
     * Converte todas as entidades HTML para os seus caracteres
     * @param $html
     * @param string $quote_style
     * @param string $charset
     */
    private function decode(string $html){
        echo html_entity_decode($html);
    }

}
