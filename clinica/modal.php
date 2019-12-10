<?php

    include("classeModal.php");


    $f = new Form($v);
    $f->add();



    $vetor["titulo"]="login";
    $vetor["form"] = $f;

    $m = new Modal($vetor);

    $m->exibe();