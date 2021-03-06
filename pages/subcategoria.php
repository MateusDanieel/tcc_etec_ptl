       <?php
        $pegar_sub = htmlentities($parametros[2]);
        $site->atualizarViewSub($pegar_sub);
        $pegar_dados_sub = @BD::conn()->prepare("SELECT titulo FROM `subcategorias` WHERE slug = ?");
        $pegar_dados_sub->execute(array($pegar_sub));
        $fetch_sub = $pegar_dados_sub->fetchObject();
		
		$pegar_categoria = htmlentities($parametros[1]);
        $site->atualizarViewCat($pegar_categoria);
		$pegar_dados_categoria = @BD::conn()->prepare("SELECT titulo FROM `categorias` WHERE slug = ?");
        $pegar_dados_categoria->execute(array($pegar_categoria));
        $fetch_cat = $pegar_dados_categoria->fetchObject();
        ?>
        <h2 class="subcat_titulo"><?php echo $fetch_cat->titulo.' - '.$fetch_sub->titulo;?></h2>
        <div id="box_subcategoria">
            <?php   
                    $pegar_categoria = htmlentities($parametros[1]);
             $pg = (isset($_GET['pagina'])) ? (int)htmlentities($_GET['pagina']) : '1';
$maximo = '9';
$inicio = (($pg * $maximo) - $maximo);
                 
                    $sql = "SELECT * FROM `produtos` WHERE categoria = ? AND subcategoria = ? ORDER BY ID DESC LIMIT $inicio, $maximo";
                    $executar_cat = @BD::conn()->prepare($sql);
                    $executar_cat->execute(array($pegar_categoria, $pegar_sub));
                    
                    if($executar_cat->rowCount() == 0){
                        echo '<p align="center">Não existem produtos nesta categoria</p>';
                    }else{
                        while($produto = $executar_cat->fetchObject()){
                ?>
		<div class="cat_produto">
                    <a href="<?php echo PATH.'/produto/'.$produto->slug;?>">
                        <img class="img_prod_cat" src="<?php echo PATH.'/produtos_img/'.$produto->img_padrao;?>" border="0" title="" alt="" />
                        <span class="tit_prod_cat"><?php echo @$produto->titulo; ?></span>
                        <span class="cat_prod_tit"><?php echo @$fetch_cat->titulo,' - ',@$fetch_sub->titulo; ?></span>
                        <span class="preco_prod_cat_anterior"><strike>De: R$ <?php echo number_format($produto->valor_anterior ,2,",","."); ?></strike></span>
                        <span class="preco_prod_cat">Por: R$ <?php echo number_format($produto->valor_atual ,2,",","."); ?></span>
                    </a>
				</div>
                <?php }}?>
                
                
                
                
            <div id="paginator">
<?php
	$sql_res = @BD::conn()->prepare("SELECT * FROM `produtos` WHERE categoria = ? AND subcategoria = ?");
	$sql_res->execute(array($pegar_categoria, $pegar_sub));
	$total = $sql_res->rowCount();
	$pags = ceil($total/$maximo);
	$links = '5';
	
	echo '<span class="page">Página: '.$pg.' de '.$pags.'</span>';
	for($i = $pg-$links; $i<=$pg-1;$i++){
		if($i<=0){}else{
			echo '<a href="'.PATH.'../categoria/'.$pegar_categoria.'/'.$pegar_sub.'&pagina='.$i.'">'.$i.'</a>';
		}
	}echo '<strong>'.$pg.'</strong>';
	
	for($i = $pg+1; $i<=$pg+$links; $i++){
		if($i>$pags){}else{
			echo '<a href="'.PATH.'../categoria/'.$pegar_categoria.'/'.$pegar_sub.'&pagina='.$i.'">'.$i.'</a>';
		}
	}
	echo '<a href="'.PATH.'../categoria/'.$pegar_categoria.'/'.$pegar_sub.'&pagina='.$pags.'">Última Página</a>';
                
                
?>
</div>


        </div>