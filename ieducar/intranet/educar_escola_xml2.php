<?php
/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
	*																	     *
	*	@author Prefeitura Municipal de Itaja�								 *
	*	@updated 29/03/2007													 *
	*   Pacote: i-PLB Software P�blico Livre e Brasileiro					 *
	*																		 *
	*	Copyright (C) 2006	PMI - Prefeitura Municipal de Itaja�			 *
	*						ctima@itajai.sc.gov.br					    	 *
	*																		 *
	*	Este  programa  �  software livre, voc� pode redistribu�-lo e/ou	 *
	*	modific�-lo sob os termos da Licen�a P�blica Geral GNU, conforme	 *
	*	publicada pela Free  Software  Foundation,  tanto  a vers�o 2 da	 *
	*	Licen�a   como  (a  seu  crit�rio)  qualquer  vers�o  mais  nova.	 *
	*																		 *
	*	Este programa  � distribu�do na expectativa de ser �til, mas SEM	 *
	*	QUALQUER GARANTIA. Sem mesmo a garantia impl�cita de COMERCIALI-	 *
	*	ZA��O  ou  de ADEQUA��O A QUALQUER PROP�SITO EM PARTICULAR. Con-	 *
	*	sulte  a  Licen�a  P�blica  Geral  GNU para obter mais detalhes.	 *
	*																		 *
	*	Voc�  deve  ter  recebido uma c�pia da Licen�a P�blica Geral GNU	 *
	*	junto  com  este  programa. Se n�o, escreva para a Free Software	 *
	*	Foundation,  Inc.,  59  Temple  Place,  Suite  330,  Boston,  MA	 *
	*	02111-1307, USA.													 *
	*																		 *
	* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */
	header( 'Content-type: text/xml' );

	require_once( "include/clsBanco.inc.php" );
	require_once( "include/funcoes.inc.php" );

  require_once 'Portabilis/Utils/DeprecatedXmlApi.php';
  Portabilis_Utils_DeprecatedXmlApi::returnEmptyQueryUnlessUserIsLoggedIn();

	echo "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<query xmlns=\"sugestoes\">\n";
	if( is_numeric( $_GET["ins"] ) )
	{
		$db = new clsBanco();
		$db->Consulta( "
				SELECT
					cod_escola
					, fantasia as nome
				FROM
					pmieducar.escola
					, cadastro.juridica
				WHERE
					ref_cod_instituicao = {$_GET["ins"]}
					AND idpes = ref_idpes
					AND ativo = 1
			UNION
				SELECT
					cod_escola
					, nm_escola
				FROM
					pmieducar.escola
					, pmieducar.escola_complemento
				WHERE
					ref_cod_instituicao = {$_GET["ins"]}
					AND cod_escola = ref_cod_escola
					AND escola.ativo = 1
				ORDER BY 2 ASC
			" );
		while ( $db->ProximoRegistro() )
		{
			list( $cod, $nome ) = $db->Tupla();
      $nome = htmlspecialchars(Portabilis_String_Utils::toUtf8($nome));
			echo "	<escola cod_escola=\"{$cod}\">{$nome}</escola>\n";
		}
	}
	echo "</query>";
?>
