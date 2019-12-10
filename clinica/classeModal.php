<?php

    class Modal{
        
        private $titulo;
        private $form;
        private $acao;

        public function __construct($vetor){
            $this->titulo = $vetor["titulo"];
            $this->acao = $vetor["acao"];
            $this->form = $vetor["form"];
        }

        public function exibe(){
            echo '
            <div class="container">
                <div class="card" style="width:300px" style="height:200px">
                <img class="card-img-top" src="img/123.png" alt="Card image" style="width:100%">
                <div class="card-body">
                <form action="validador_login.php" method="post" class="was-validated">
                      <div class="container"><br />
                        <button type="button" class="btn btn-primary btn-lg" id="myBtn">'.$this->acao.'</button>
                        
                <div class="modal fade" id="myModal" role="dialog">
            
                <div class="modal-dialog">
                <!-- Modal content-->
                <div class="modal-content">
                    <div class="modal-header" style="padding:35px 50px;">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4><span class="glyphicon glyphicon-lock"></span>'.$this->titulo.'</h4>
                    </div>
                    <div class="modal-body" style="padding:40px 50px;">';

            $this->form->exibe();
            echo '</div>

                <div class="modal-footer">
                <button type="submit" class="btn btn-danger btn-default pull-left" data-dismiss="modal"><span class="glyphicon glyphicon-remove"></span> Cancel</button>
                </div>
             </div> <!-- fechou modal-content -->
             </div>  <!-- fechou modal-dialog -->
            </div>  <!-- fechou modal-fade -->
            </div>  <!-- fechou container -->
            </div>  <!-- fechou demo -->
            </div>  <!-- fechou card-body -->
            </div>  <!-- fechou card -->
            </div>  <!-- fechou container -->';
        }
    }


?>