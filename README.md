# Docs CKEDITOR Builder

<p>O CKEDITOR é um editor HTML WYSIWYG, baseado em bootstrap e ampla compatibilidade com navegadores, incluindo navegadores legados. Esse projeto tem por objetivo explorar essa biblioteca construída em javascript.</p>

<p>Quer saber mais sobre o CKEDITOR? Acesse o site oficial: <a href="https://ckeditor.com" target="_blank">https://ckeditor.com</a>.</p>

### Como utilizar?

<p>Antes de mais nada é necessário chamar o arquivo que contém a classe no seu arquivo principal</p>

	require_once "src/Ckeditor.php";
    
<p>E logo em seguida crie um objeto da classe:</p>
    
    $builder = new Ckeditor();

<p>Os seguintes métodos estarão disponíveis para acesso</p>
	
<ul>

<li>
<strong><code>setPath(string $path)</code></strong>: 
com esse método é possivel alterar o caminho original que aponta para os arquivos js e css da biblioteca do CKEDITOR;	
<p>

    $builder->setPath('docs/templates/plugins/');    

</p>

</li>
    
<li>
<strong><code>setEditable(bool $editable)</code></strong>: 
esse método deve ser chamado logo depois da instância da classe, passando como parâmentro o boleano <code>true</code>. Somente assim, será possivel criar os elementos com editor;	
<p>

    $builder->setEditable(true);    

</p>
</li>
    
<li>
<strong><code>setSettings(array $settings)</code></strong>: 
esse método altera as configurações padrões do editor.
<p>
   	
    $builder->setSettings([
        'enterMode' => 'CKEDITOR.ENTER_BR',
        'language' => '"fr"',
        'uiColor' => '"#9AB8F3"'
    ]);    
</p>
<p>
Consulte a <a href="https://ckeditor.com/docs/ckeditor4/latest/guide/dev_configuration.html" target="_blank">documentação, contendo algumas das configurações, clicando aqui.</a>
</p>
</li>
    
<li>
<strong><code>setTag(string $tag)</code></strong>: 
esse método altera a tag utilizada para criar os elementos de edição. A tag padrão já definida é o <code>textarea</code>.
<p>
   	
    $builder->setTag('span');
    $builder->setTag('div');
        
</p>
</li>
    
    
<li>
<strong><code>setToolbar(array $toolbar)</code></strong>: 
esse método altera o toolbar que já está definido na inicialização do construtor da classe. Caso tenha interesse de alterar essa configuração, passe um array como os novos componentes que você deseja. Tanbém é possível definir um toolbar especifíco a um elemento.
<p>
   	
    $builder->setToolbar([
        'basicstyles' => ['Bold', 'Italic'],
        'paragraph' => ['JustifyLeft', 'JustifyCenter', 'JustifyRight', 'JustifyBlock'],
        'links' => ['Link']
    ]);
        
</p>
<p>
Consulte a <a href="https://ckeditor.com/docs/ckeditor4/latest/guide/dev_toolbar.html" target="_blank">documentação sobre este tópico.</a>
</p>
</li>
    
    
<li>
<strong><code>getCss()</code> e <code>getJs()</code></strong>:
são duas funções que devem ser chamadas logo na inicialização do corpo html dentro da tag <code> head </code>. Estas funções tem por objetivo criar uma ponte com os arquivos de css e js do ckeditor.
<p>
   	
    $builder->getCss();
    
    // chamada dos arquivos do jquery e bootstrap
    
    $builder->getJs();
        
</p>
</li>
    
    
<li>
<strong><code>article($component, $content, $settings = array(), $attributes = array())</code> (*) </strong>: é uma das funções de inicialização do editor. Ao chamar esse método o ckeditor será iniciado. Possui dois parâmetros obrigatórios. 
</li>

<li>
<strong><code>inline($component, $content, $settings = array(), $attributes = array())</code> (*) </strong>: é uma outra função de inicialização do editor. Ao chamar esse método o ckeditor será iniciado, só que no modo <code>inline</code>. 

O <code>$component</code> é referente ao atributo <code>id</code> de uma tag. <br>
O <code>$content</code> é o conteúdo que estará dentro dessa tag, ou seja, o <code>value</code>. <br>
O <code>$settings</code> é um array que possui a mesma funcionalidade da função <code>setSettings()</code>. Nesse parâmetro é possível definir configurações somente para aquela instância do editor.<br>
O <code>$attributes</code> é um array onde você pode definir os atributos da tag que será utilizada para a edição.
<p>
   	
    $builder->article('componente1', 'Este componente é um editor de texto');
    
    $builder->inline('componente1', 'Este componente é um editor de texto', [
        'uiColor' => '"#FF0000"',
        'toolbar' => [
            'basicstyles' => ['Bold', 'Italic'],
            'paragraph' => ['JustifyLeft', 'JustifyCenter', 'JustifyRight', 'JustifyBlock'],
            'links' => ['Link']
        ]
    ]);
        
</p>
</li>


<li>
<strong><code>action(string $route, $attributes = array())</code></strong>:
com a chamada dessa função será criada um bloco contendo um botão para salvar as informações via ajax. Ela possui um parâmetro obrigatório.
 
 O <code>$route</code> deve conter uma string com o caminho do arquivo que contenha toda a lógica de programação.<br>
 O <code>$attributes</code> é um array que contém os atributos do botão, caso o usuário deseja alterar a formatação padrão do bootstrap.
<p>
   	
    $builder->action('php/actions/save_content.php');
    
    $builder->action('php/actions/save_content.php', [
        'class' => 'btn btn-success'
    ]);
                
</p>
</li>



</ul>
