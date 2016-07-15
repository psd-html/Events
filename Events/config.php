<?php if (!defined('PLX_ROOT')) exit;
# Control du token du formulaire
plxToken::validateFormToken($_POST);
# nombre d'evenements existants
$nbevents = floor(sizeof($plxPlugin->getParams())/4);

if(!empty($_POST)) {
	if (!empty($_POST['event-new']) AND !empty($_POST['date-new']) AND !empty($_POST['img-new']))  {

        # création d'un nouveau evenement
        $newevent= $nbevents + 1;
		$plxPlugin->setParam('event'.$newevent, plxUtils::strCheck($_POST['event-new']), 'cdata');
		$plxPlugin->setParam('date'.$newevent, plxUtils::strCheck($_POST['date-new']), 'cdata');
        $plxPlugin->setParam('link'.$newevent, plxUtils::strCheck($_POST['link-new']), 'cdata');
        $plxPlugin->setParam('img'.$newevent, plxUtils::strCheck($_POST['img-new']), 'cdata');
        $plxPlugin->saveParams();
        
	}else{
        
        # Mise à jour des reponses existants
        for($i=1; $i<=$nbevents; $i++) {
            if($_POST['delete'.$i] != "1" AND !empty($_POST['event'.$i])){ 

                
                #mise a jour du question et reponse
                $plxPlugin->setParam('event'.$i, plxUtils::strCheck($_POST['event'.$i]), 'cdata');
                $plxPlugin->setParam('date'.$i, plxUtils::strCheck($_POST['date'.$i]), 'cdata');
                $plxPlugin->setParam('link'.$i, plxUtils::strCheck($_POST['link'.$i]), 'cdata');
                $plxPlugin->setParam('img'.$i, plxUtils::strCheck($_POST['img'.$i]), 'cdata');
                $plxPlugin->saveParams();
            
            }elseif($_POST['delete'.$i] == "1"){
                $plxPlugin->setParam('event'.$i, '', '');
                $plxPlugin->setParam('date'.$i, '', '');
                $plxPlugin->setParam('link'.$i, '', '');
                $plxPlugin->setParam('img'.$i, '', '');
                $plxPlugin->saveParams();
            }
        }
    }
}
# mise à jour du nombre de reponses existants
	$nbevents = floor(sizeof($plxPlugin->getParams())/4);
?>





<!-- navigation sur la page configuration du plugin -->
<nav id="tabby-1" class="tabby-tabs" data-for="example-tab-content">
	<ul>
		<li><a data-target="tab1" class="active" href="#">Ajouter un événement</a></li>
		<li><a data-target="tab2" href="#">gestion des événements</a></li>
		<li><a data-target="tab3" href="#">Information</a></li>
	</ul>
</nav>

<!-- contenu de la page configuration -->
<div class="tabby-content" id="example-tab-content">

<!-- page pour afficher les témoignages -->
<div data-tab="tab1">

    <h2>Gestion des événements</h2>

    <div class="formulaire">
        <!-- reponses déja créés -->
        <form action="parametres_plugin.php?p=Events" method="post">
            <fieldset>
                <table class="full-width">
                    <thead>
                        <tr>
                            <th class="id">N°</th>
                            <th>Evénement</th>
                            <th>Date</th>
                            <th>Lien</th>
                            <th>image</th>
                            <th class="checkbox">Supprimer</th>
                        </tr>
                    </thead>

                    <tbody>
                        <?php for($i=1; $i<=$nbevents; $i++) {?>
                        <?php $event = $plxPlugin->getParam(event.$i);
                        if(!empty($event)) { ?>
                        
                        <tr class="line-<?php echo $i%2 ?>">
                            <td>
                                <?php echo $i; ?>
                            </td>
                            
                            <td class="question">
                                <input type="text" name="event<?php echo $i; ?>" value="<?php echo $plxPlugin->getParam(event.$i); ?>" />
                            </td>
                            
                            <td class="reponse">
                                <input type="text" name="date<?php echo $i; ?>" class="date" value="<?php echo $plxPlugin->getParam(date.$i); ?>" />
                            </td>

                            <td class="reponse">
                                <input type="text" name="link<?php echo $i; ?>" value="<?php echo $plxPlugin->getParam(link.$i); ?>" />
                            </td>
                            
                            <td class="reponse">

                                <input type="img" name="img<?php echo $i; ?>" value="<?php echo $plxPlugin->getParam(img.$i); ?>" />
                            </td>
                            
                            <td class="checkbox">
                                <input type="checkbox" name="delete<?php echo $i; ?>" value="1">
                            </td>
                        </tr>
                            <?php }; ?>
                                <?php }; ?>
                    </tbody>

                </table>
            </fieldset>

                    <p class="in-action-bar">
                        <?php echo plxToken::getTokenPostMethod() ?>
                        <input class="bt" type="submit" name="submit" value="Mettre à jour" />
                    </p>
        </form>
    </div>

</div><!-- de la page 1 -->

<!-- page pour créer un événement-->
<div data-tab="tab2">

<h2>Ajouter un événement</h2>

<div class="new">

 <form action="parametres_plugin.php?p=Events" method="post">
   
        <p>
            <label for="event">Nom de l'événement (Obligatoire)</label>
             <input type="text" name="event-new" value="" />
        </p>

        <p>
            <label for="date">Date (Obligatoire)</label>
            <input type="text" name="date-new" class="date" value="" />
        </p>

        <p>
            <label for="link">Lien de la page événement</label>
            <input type="text" name="link-new" value="" />
        </p>

        <p>
            <label for="avatar">Image (Obligatoire)
            <a id="toggler_thumbnail" href="javascript:void(0)" onclick="mediasManager.openPopup('img-new', true)">+</a>
            </label>

            <input id="img-new" name="img-new"  maxlength="255" value="<?php echo plxUtils::strCheck($plxPlugin->getParam("img-new")) ?>">
        </p>         
           
        <p class="in-action-bar">
            <?php echo plxToken::getTokenPostMethod() ?>
            <input class="bt" type="submit" name="submit" value="Valider" />
        </p>

    </form>
</div>

</div><!-- fin de la page 1 -->

<!-- page de configuration -->
<div data-tab="tab3">
    <h2><?php $plxPlugin->lang('L_NAV_LIEN3') ?></h2>

    <p>Pour afficher le plugin dans votre sidebar:</p>

    <p>
        <code>
                &lt;h3&gt;Evénements&lt;/h3&gt; <br>
               &lt;?php eval($plxShow->callHook("Events")); ?&gt;
        </code>

    </p>

    <p>Pour vérifier les mises à jour des plugins: <a href="http://nextum.fr">NextuM</a>   


</div><!-- fin de la page 3 -->


</div>




<script src="http://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
<script src="<?php echo PLX_PLUGINS ?>Events/app/jquery.tabby.js"></script>
<script src="<?php echo PLX_PLUGINS ?>Events/app/datepicker.min.js"></script>
<script src="<?php echo PLX_PLUGINS ?>Events/app/datepicker.fr.js"></script>

<script>
    $(document).ready(function(){
        $('#tabby-1').tabby();
    });
</script>
<script>

    $('.date').datepicker({
    language: 'fr',
    
        
    minDate: new Date() // Now can select only dates, which goes after today
        
        
})
</script>