<?php
$hassidepre = $PAGE->blocks->region_has_content('side-pre', $OUTPUT);
$hassidepost = $PAGE->blocks->region_has_content('side-post', $OUTPUT);
echo $OUTPUT->doctype(); ?>
<html <?php echo $OUTPUT->htmlattributes() ?> >
  <head>
    <title><?php echo $PAGE->title ?></title>
    <?php echo $OUTPUT->standard_head_html() ?>
  </head>
  <body id="<?php echo $PAGE->bodyid; ?>" class="<?php echo $PAGE->bodyclasses; ?>">
    <?php echo $OUTPUT->standard_top_of_body_html() ?>
    <div id="page">
      <div id="header_bg">
        <div id="menu-subportales">
          <ul class="botones-subportales">
            <li><a href="http://www.eafit.edu.co/investigacion">Investigación</a></li>
	    <li><a href="http://www.eafit.edu.co/cice">Cice</a></li>
	    <li><a href="http://www.eafit.edu.co/cec">Educación Continua</a></li>
	    <li><a href="http://www.eafit.edu.co/idiomas">Idiomas</a></li>
            <li><a href="http://www.eafit.edu.co/cultura">Cultura</a></li>
            <li><a href="http://www.eafit.edu.co/biblioteca">Biblioteca</a></li>
	    <li class="last"><a href="http://www.eafit.edu.co/international">International</a></li>
          </ul>
        </div>

        <div id="header">
          <img src="<?php echo $OUTPUT->pix_url('logosimbolo_eafit', 'theme') ?>" />
          <img src="<?php echo $OUTPUT->pix_url('logo', 'theme') ?>" />
        </div>
      </div>
    <div class="nav">
      <ul>
        <li><a href="#">Inicio</a></li>
        <li><a href="#">Información</a></li>
        <li><a href="#">Equipos</a></li>
        <li><a href="#">Reservas</a></li>
        <li><a href="#">Cursos</a></li>
      </ul>
    </div>


      <div id="page-content">
        <div id="region-main-box">
          <div id="region-post-box">
            <div id="region-main-wrap">
              <div id="region-main">
                <div class="region-content">
                  <?php echo core_renderer::MAIN_CONTENT_TOKEN ?>
                </div>
              </div>
            </div>
            <?php if ($hassidepre) { ?>
            <div id="region-pre">
              <div class="region-content">
                <?php echo $OUTPUT->blocks_for_region('side-pre') ?>
              </div>
            </div>
            <?php } ?>

            <?php if ($hassidepost) { ?>
            <div id="region-post">
              <div class="region-content">
                <?php echo $OUTPUT->blocks_for_region('side-post') ?>
              </div>
            </div>
            <?php } ?>
          </div>
        </div>
      </div>
      <?php if (empty($PAGE->layout_options['nofooter'])) { ?>
      <div id="page-footer" class="clearfix">
        <p class="helplink"><?php echo page_doc_link(get_string('moodledocslink')) ?></p>
        <?php
          echo $OUTPUT->login_info();
          echo $OUTPUT->home_link();
          echo $OUTPUT->standard_footer_html();
        ?>
      </div>
      <?php } ?>
    </div>

    <div id="footer_bg">
      <div id="footer">
        <div id="info-contacto">
          <span class="amarillo">Universidad EAFIT</span><br />
          Línea de atención al usuario<br />
          Medellín: (57) (4) - 448 95 00<br />
          Resto del país: 01 8000 515 900<br />
          Conmutador: (57) (4) - 2619500<br />
          Correo: admisiones.registro@eafit.edu.co<br />
          Dirección: Carrera 49 N&deg; 7 Sur - 50<br />
          Medellín - Colombia - Suramérica<br />
          Copyright 2010 (c) <br />
          Todos los Derechos Reservados</div>

        <div class="lista-footer">
          <ul class="botones-footer">
            <li class="amarillo">Alianzas</li>
            <li><a href="http://www.colombiaaprende.edu.co" target="_blank">Colombia Aprende</a></li>
	    <li><a href="http://www.colombiaespasion.com" target="_blank">Colombia es Pasión</a></li>
	    <li><a href="http://www.medellincomovamos.org" target="_blank">Medellín cómo vamos</a></li>
	    <li><a href="http://www.faae.org.co" target="_blank">FAAE</a></li>
	    <li><a href="http://www.birdantioquia.org.co" target="_blank">Bird Antioquia</a></li>
	    <li><a href="http://www.universia.net.co" target="_blank">Universia</a></li>
	    <li><a href="http://www.ruana.edu.co" target="_blank">Ruana</a></li>
	    <li><a href="http://www.renata.edu.co" target="_blank">Renata</a></li>
	    <li><a href="http://www.cis.org.co" target="_blank">Cis</a></li>
          </ul>
        </div>

        <div class="lista-footer">
          <ul class="botones-footer">
            <li class="amarillo">Servicios Web</li>
            <li><a href="/servicios-en-linea/aplicaciones-web/Paginas/aplicaciones-web.aspx" target="_blank">Aplicaciones Web</a></li>
	    <li><a href="http://portus.eafit.edu.co" target="_blank">Correo Web</a></li>
	    <li><a href="http://www.eafit.edu.co/ulises" target="_blank">Ulises</a></li>
	    <li><a href="http://interactiva.eafit.edu.co" target="_blank">EAFIT Interactiva</a></li>
	    <li><a href="http://entrenos.eafit.edu.co" target="_blank">Intranet Entrenos</a></li>
	    <li><a href="http://www.eafit.edu.co/agencia-noticias" target="_blank">Agencia de Noticias</a></li>
	    <li><a href="http://envivo.eafit.edu.co" target="_blank">Canal Envivo</a></li>
	    <li><a href="http://acustica.eafit.edu.co" target="_blank">Emisora Acústica</a></li>
	    <li><a href="http://www.eafit.edu.co/servicios-en-linea/Paginas/mapa-sitio.aspx" target="_blank">Mapa de Sitio</a></li>
          </ul>
        </div>

        <div class="lista-footer">
          <ul class="botones-footer">
            <li class="amarillo">Contáctenos</li>
            <li><a href="/institucional/contacto/Paginas/contacto-eafit.aspx" target="_blank">Contacto</a></li>
	    <li><a href="http://www2.eafit.edu.co/bisu" target="_blank">Sugerencias</a></li>
	    <li><a href="http://www.eafit.edu.co/registro" target="_blank">Suscríbase</a></li>
	    <li><a href="/servicios-en-linea/Paginas/directorio-redes-sociales.aspx" target="_blank">Directorio de redes sociales</a></li>
	    <li><a href="http://www.elempleo.com/sitios_empresariales/eafit/index.asp" target="_blank">Trabaje con nosotros</a><a href="#"></a></li>
          </ul>
        </div>
      </div>
      <div id="linea-footer"></div>
    </div>



    <?php echo $OUTPUT->standard_end_of_body_html() ?>
</body>
</html>









