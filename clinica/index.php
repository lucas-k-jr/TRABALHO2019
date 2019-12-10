<?php

	//include("interfaceExibicao.php");
	require_once("classeLayout/classeCabecalhoHTML.php");
	require_once("classeLayout/classeTabela.php");
	require_once("cabecalho.php");
	require_once("conexao.php");
	
	require_once("classeControllerBD.php");

    echo'<!DOCTYPE html>
    <html lang="PT-BR">
    <head>
      <meta charset="utf-8" />
      <meta name="viewport" content="width=device-width, initial-scale=1">
      <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
      <link href="https://fonts.googleapis.com/css?family=Montserrat" rel="stylesheet" type="text/css">
      <link href="https://fonts.googleapis.com/css?family=Lato" rel="stylesheet" type="text/css">
      <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/css/bootstrap.min.css">
      <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
      <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
      <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
      <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
      <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/js/bootstrap.min.js"></script>
      <style>
        .nav-item{
          font-size: 20px
        }
        body{
          font: 400 15px Lato, sans-serif;
          line-height: 1.8;
          color: #818181;
        }
        .modal-footer {
          background-color: #f9f9f9;
        }
        label{
        color:white;
        }
        .nav-item-sair{
        font-size:20px;
        }
        h2 {
            font-size: 24px;
            text-transform: uppercase;
            color: #303030;
            font-weight: 600;
            margin-bottom: 30px;
        }
        h4 {
            font-size: 19px;
            line-height: 1.375em;
            color: #303030;
            font-weight: 400;
            margin-bottom: 30px;
        }  
        .jumbotron {
            background-color: lightseagreen ;
            color: #fff;
            padding: 100px 25px;
            font-family: Montserrat, sans-serif;
        }
        .container-fluid {
            padding: 60px 50px;
        }
        .bg-grey {
            background-color: #f6f6f6;
        }
        .logo-small {
            color: #f4511e;
            font-size: 50px;
        }
        .logo {
            color: #f4511e;
            font-size: 200px;
        }
        .thumbnail {
            padding: 0 0 15px 0;
            border: none;
            border-radius: 0;
        }
        .thumbnail img {
            width: 100%;
            height: 100%;
            margin-bottom: 10px;
        }
        .carousel-control.right, .carousel-control.left {
            background-image: none;
            color: #f4511e;
        }
        .carousel-indicators li {
            border-color: #f4511e;
        }
        .carousel-indicators li.active {
            background-color: #f4511e;
        }
        .item h4 {
            font-size: 19px;
            line-height: 1.375em;
            font-weight: 400;
            font-style: italic;
            margin: 70px 0;
        }
        .item span {
            font-style: normal;
        }
        .panel {
            border: 1px solid #f4511e; 
            border-radius:0 !important;
            transition: box-shadow 0.5s;
        }
        .panel:hover {
            box-shadow: 5px 0px 40px rgba(0,0,0, .2);
        }
        .panel-footer .btn:hover {
            border: 1px solid #f4511e;
            background-color: #fff !important;
            color: #f4511e;
        }
        .panel-heading {
            color: #fff !important;
            background-color: #f4511e !important;
            padding: 25px;
            border-bottom: 1px solid transparent;
            border-top-left-radius: 0px;
            border-top-right-radius: 0px;
            border-bottom-left-radius: 0px;
            border-bottom-right-radius: 0px;
        }
        .panel-footer {
            background-color: white !important;
        }
        .panel-footer h3 {
            font-size: 32px;
        }
        .panel-footer h4 {
            color: #aaa;
            font-size: 14px;
        }
        .panel-footer .btn {
            margin: 15px 0;
            background-color: #f4511e;
            color: #fff;
        }
        footer .glyphicon {
            font-size: 20px;
            margin-bottom: 20px;
            color: #f4511e;
        }
        .slideanim {visibility:hidden;}
        .slide {
            animation-name: slide;
            -webkit-animation-name: slide;
            animation-duration: 1s;
            -webkit-animation-duration: 1s;
            visibility: visible;
        }
        @keyframes slide {
            0% {
            opacity: 0;
            transform: translateY(70%);
            } 
            100% {
            opacity: 1;
            transform: translateY(0%);
            }
        }
        @-webkit-keyframes slide {
            0% {
            opacity: 0;
            -webkit-transform: translateY(70%);
            } 
            100% {
            opacity: 1;
            -webkit-transform: translateY(0%);
            }
        }
        @media screen and (max-width: 768px) {
            .col-sm-4 {
            text-align: center;
            margin: 25px 0;
            }
            .btn-lg {
            width: 100%;
            margin-bottom: 35px;
            }
        }
        @media screen and (max-width: 480px) {
            .logo {
            font-size: 150px;
            }
        }
      </style>
      </style>
    </head>
    <body id="myPage" data-spy="scroll" data-offset="60">
      <!--Desconectar-->
        <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
            <div class="modal-dialog modal-sm" role="document">
              <div class="modal-content">
                <div class="modal-header">
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span>
                  </button>
                  <h4 class="modal-title" id="myModalLabel">Atenção</h4>
                </div>
                <div class="modal-body">
                  Deseja desconectar?
                </div>
                <div class="modal-footer">
                  <button type="button" class="btn btn-default" data-dismiss="modal">Não</button>
                  <a class="btn btn-primary" href="<c:url value=" /logout "/>Sim</a>
                </div>
              </div>
            </div>
          </div>
    
        <nav class="navbar navbar-expand-sm bg-dark navbar-dark fixed-top">
            <!-- Dropdown -->
            <nav class="navbar navbar-expand-sm bg-dark navbar-dark fixed-top">
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <li class="nav-item">
                            <a class="nav-link" href="inicial_adm.html">Página Inicial</a>
                        </li>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbardrop" data-toggle="dropdown">
                            Cadastrar
                        </a>
                        <div class="dropdown-menu">
                            <a class="dropdown-item" href="../funcionario/form_func_c_c.html">Cliente</a>
                            <a class="dropdown-item" href="../funcionario/form_func_c_con.html">Consulta</a>
                        </div>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbardrop" data-toggle="dropdown">
                            Listagem e Alteração
                        </a>
                        <div class="dropdown-menu">
                            <a class="dropdown-item" href="../funcionario/list_alt_c.html">Cliente</a>
                            <a class="dropdown-item" href="../funcionario/list_alt_con.html">Consulta</a>
                        </div>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbardrop" data-toggle="dropdown">
                            Fale-Conosco
                        </a>
                        <div class="dropdown-menu">
                            <a class="dropdown-item" href="../cliente/avaliacao_cliente.html">Avaliação</a>
                        </div>
                        <li class="nav-item">
                          <a class="nav-link" href="sobre3.html">Sobre nós</a>
                        </li>
                    </li>
                    <li class="nav-item-sair">
                      <a href="index.html" data-toggle="modal" data-target="#myModal" class="nav-link">Sair</a>
                    </li>
                </ul>
            </nav>
        </nav>
        <div class="jumbotron text-center">
                <h1>UniDente</h1> 
                <p>A melhor clínica da região</p> 
        </div>
              
              <!-- Container (About Section) -->
              <div id="about" class="container-fluid">
                <div class="row">
                  <div class="col-sm-8">
                    <h2>Sobre nossa empresa</h2><br>
                    <h4>Nossa empresa busca o conforto de todos nossos clientes como algo essencial e primordial. A clínica UniDente tem uma tecnologia de ponto na qual podemos dizer que é umas das melhores do estado. Nosso dever é fazer a melhor experiência nosso público.</h4><br>
                    <p style = "color:black">Contamos com profissionais de qualidade internacional, que procuram sempre o melhor da UniDente e nos nossos clientes. Visite-nos e marque uma conulta</p>
                  </div>
                  <div class="col-sm-4">
                    <span class="glyphicon glyphicon-signal logo"></span>
                  </div>
                </div>
              </div>
    
              <!-- Container (Services Section) -->
              <div id="services" class="container-fluid text-center">
                <h2>SERVIÇOS</h2>
                <h4>O melhor da região</h4>
                <br>
                <div class="row slideanim">
                  <div class="col-sm-4">
                    <span class="glyphicon glyphicon-off logo-small"></span>
                    <h4>PODER</h4>
                    <p>Investimentos pesados da UniDente..</p>
                  </div>
                  <div class="col-sm-4">
                    <span class="glyphicon glyphicon-heart logo-small"></span>
                    <h4>AMOR</h4>
                    <p>Conforto completo para todos..</p>
                  </div>
                  <div class="col-sm-4">
                    <span class="glyphicon glyphicon-lock logo-small"></span>
                    <h4>SERVIÇOS DE PONTA</h4>
                    <p>Clínica priorizada ao clientes..</p>
                  </div>
                </div>
                <br><br>
                <div class="row slideanim">
                  <div class="col-sm-4">
                    <span class="glyphicon glyphicon-leaf logo-small"></span>
                    <h4>TECNOLOGIA</h4>
                    <p>Tecnologia de ponta..</p>
                  </div>
                  <div class="col-sm-4">
                    <span class="glyphicon glyphicon-certificate logo-small"></span>
                    <h4>CERTIFICADO</h4>
                    <p>Médicos hiper capacitados..</p>
                  </div>
                  <div class="col-sm-4">
                    <span class="glyphicon glyphicon-wrench logo-small"></span>
                    <h4 style="color:#303030;">TRABALHO DURO</h4>
                    <p>Trabalhamos para te oferecer o máximo..</p>
                  </div>
                </div>
              </div>
              
              <!-- Container (Portfolio Section) -->
              <div id="portfolio" class="container-fluid text-center bg-grey">
                <h2>Portfolio</h2><br>
                <h4>O que fazemos?</h4>
                <div class="row text-center slideanim">
                  <div class="col-sm-4">
                    <div class="thumbnail">
                      <img src="img/site1.jpg" alt="1" width="400" height="300">
                      <p><strong>Branqueamento</strong></p>
                      <p>Total efetividade</p>
                    </div>
                  </div>
                  <div class="col-sm-4">
                    <div class="thumbnail">
                      <img src="img/site2.jpg" alt="2" width="400" height="300">
                      <p><strong>Tratamento</strong></p>
                      <p>Os melhores resultados</p>
                    </div>
                  </div>
                  <div class="col-sm-4">
                    <div class="thumbnail">
                      <img src="img/site2.png" alt="San Francisco" width="400" height="300">
                      <p><strong>Implante</strong></p>
                      <p>Próteses perfeitas para você</p>
                    </div>
                  </div>
                </div><br>
                
                <h2>O que as pessoas comentam</h2>
                <div id="myCarousel" class="carousel slide text-center" data-ride="carousel">
                  <!-- Indicators -->
                  <ol class="carousel-indicators">
                    <li data-target="#myCarousel" data-slide-to="0" class="active"></li>
                    <li data-target="#myCarousel" data-slide-to="1"></li>
                    <li data-target="#myCarousel" data-slide-to="2"></li>
                  </ol>
              
                  <!-- Wrapper for slides -->
                  <div class="carousel-inner" role="listbox">
                    <div class="item active">
                      <h4>"Esta empresa é a melhor. Estou muito feliz com o resultado!"<br><span>Nicolas Silva, Cliente</span></h4>
                    </div>
                    <div class="item">
                      <h4>"A melhor, número 1 da região!!"<br><span>Rebeca Costa, Cliente</span></h4>
                    </div>
                    <div class="item">
                      <h4>"Posso dizer que é a mais bem equipada?"<br><span>Lucas Rodi, Cliente</span></h4>
                    </div>
                  </div>
              
                  <!-- Left and right controls -->
                  <a class="left carousel-control" href="#myCarousel" role="button" data-slide="prev">
                    <span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>
                    <span class="sr-only">Previous</span>
                  </a>
                  <a class="right carousel-control" href="#myCarousel" role="button" data-slide="next">
                    <span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span>
                    <span class="sr-only">Next</span>
                  </a>
                </div>
              </div>
              
              <!-- Container (Pricing Section) -->
              <div id="pricing" class="container-fluid">
                <div class="text-center">
                  <h2>Qualidade</h2>
                  <h4>Veja toda nossa estrutura</h4>
                </div>
                <div class="row slideanim">
                  <div class="col-sm-4 col-xs-12">
                    <div class="panel panel-default text-center">
                      <div class="panel-heading">
                        <h1>Tecnologia</h1>
                      </div>
                      <div class="panel-body">
                        <p>Escaneamento intraoral</p>
                        <p>Tecnologia CAD/CAM 3D</p>
                        <p>Cirurgia guiada</p>
                        <p>Prontuário eletrônico</p>
                        <p>Prontuário por digital</p>
                      </div>
                      <div class="panel-footer">
                        <h3>UniDente</h3>
                      </div>
                    </div>      
                  </div>     
                  <div class="col-sm-4 col-xs-12">
                    <div class="panel panel-default text-center">
                      <div class="panel-heading">
                        <h1>Local</h1>
                      </div>
                      <div class="panel-body">
                        <p>Centro da cidade</p>
                        <p>Acessível a todos</p>
                        <p>Estrutura nova</p>
                        <p>Limpeza extrema</p>
                        <p>Elavadores</p>
                      </div>
                      <div class="panel-footer">
                        <h3>UniDente</h3>
                      </div>
                    </div>      
                  </div>       
                  <div class="col-sm-4 col-xs-12">
                    <div class="panel panel-default text-center">
                      <div class="panel-heading">
                        <h1>Atendimento</h1>
                      </div>
                      <div class="panel-body">
                        <p>Profissionais qualificados</p>
                        <p>Atendimento de alto padrão</p>
                        <p>Agilidade no atendimento</p>
                        <p>Segurança</p>
                        <p>Mobilidade aos Clientes</p>
                      </div>
                      <div class="panel-footer">
                        <h3>UniDente</h3>
                      </div>
                    </div>      
                  </div>    
                </div>
              </div>
              </body>
              </html>';
              ?>
            
              <script>
              $(document).ready(function(){
                // Adicione rolagem suave a todos os links na barra de navegação + link de rodapé
                $(".navbar a, footer a[href='#myPage']").on('click', function(event) {
                  // Certifique-se de que this.hash tenha um valor antes de substituir o comportamento padrão
                  if (this.hash !== "") {
                    // Impedir o comportamento padrão do clique na âncora
                    event.preventDefault();
              
                    // Store hash
                    var hash = this.hash;
              
                    // Usando o método animate () do jQuery para adicionar rolagem de página suave
                    // O número opcional (900) especifica o número de milissegundos necessário para rolar para a área especificada
                    $('html, body').animate({
                      scrollTop: $(hash).offset().top
                    }, 900, function(){
                 
                      // Adicione o hash (#) ao URL ao concluir a rolagem (comportamento padrão do clique)
                      window.location.hash = hash;
                    });
                  } // fim do if
                });
                
                $(window).scroll(function() {
                  $(".slideanim").each(function(){
                    var pos = $(this).offset().top;
              
                    var winTop = $(window).scrollTop();
                      if (pos < winTop + 600) {
                        $(this).addClass("slide");
                      }
                  });
                });
              })
              </script>