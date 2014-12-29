<?php
require('fpdf.php');
class PDF_HTML extends FPDF{
/*********************************************************
*FUNCIONES PARA CABERCERA PIE DE PAGINA Y TABLA DE COLORES
***********************************************************/
function Header(){
		$this->Image('banner.jpg',5,5,205, 30);
		// Arial bold 15
		$this->SetFont('Times','B',12);
		// Movernos a la derecha
						// Salto de línea
				$this->Ln();
		}
		//Pie de página
public function Footer(){
			//Posición: a 2 cm del final
			$this->SetY(-20);
			//Arial italic 8
			$this->SetFont("Arial","I",10);
			//Dirección
			$this->Cell(0,5,utf8_decode("ACCION CENTRAL.- Carretera Nacional Vía Turen, Sector E, Planta Arroz del Alba, S.A. Píritu."),0,1,"C");
			$this->Cell(0,5,utf8_decode("Estado Portuguesa. Rif-G-20008205-4  Teléfono 0256-3361455"),0,1,"C");
			//Número de página
}
	function TablaColores($header, $base, $resp, $comp, $pro, $hijo, $hogar){
	//	$this->setX(150);
		$this->setY(102);
	//	$this->SetMargins(20, 25 , 20); 
		//Colores, ancho de línea y fuente en negrita
		$this->SetFillColor(157,152,152);
		$this->SetTextColor(0);
		$this->SetDrawColor(0,0,0);
		$this->SetLineWidth(.3);
		$this->SetFont('Arial','B',12);
		//Cabecera

		for($i=0;$i<count($header);$i++)
		  $this->Cell(70,7,$header[$i],1,0,'C',1);
		
        $this->Ln();

		//PIE DE PAGINA
		$total = $base+$pro+$hijo+$hogar+$comp+$resp;
		$total = number_format($total,2,",",".");
		$footer = array('Total Salario Integral',$total);

		//Restauración de colores y fuentes
		$this->SetFillColor(224,235,255);
		$this->SetTextColor(0);
		$this->SetFont('');
		//Datos

		$fill=false;

		//Formateando los nros :
		$base =  number_format($base,2,",",".");
		$resp =  number_format($resp,2,",",".");
		$pro =  number_format($pro,2,",",".");
		$hijo =  number_format($hijo,2,",",".");
		$comp =  number_format($comp,2,",",".");
		$hogar = number_format($hogar,2,",",".");
		//tablita ya para empezar
		$this->Cell(70,7,utf8_decode("Sueldo Básico"),'LR',0,'L',$fill);
		$this->Cell(70,7, $base,'LR',0,'C',$fill);
		$this->Ln();
		$fill=true;
		$this->Cell(70,7,"Prima Responsabilidad",'LR',0,'L',$fill);
		$this->Cell(70,7, $resp ,'LR',0,'C',$fill);
		$this->Ln();
		$fill=false;
		$this->Cell(70,7,"Prima Profesional",'LR',0,'L',$fill);
		$this->Cell(70,7, $pro,'LR',0,'C',$fill);
		$this->Ln();
		$fill=!$fill;
		$this->Cell(70,7,"Prima por Hijo",'LR',0,'L',$fill);
		$this->Cell(70,7, $hijo,'LR',0,'C',$fill);
		$this->Ln();
		$fill=false;
		$this->Cell(70,7,"Prima por Hogar",'LR',0,'L',$fill);
		$this->Cell(70,7, $hogar,'LR',0,'C',$fill);
		$this->Ln();
		$fill=true;
		$this->Cell(70,7,"Prima Complementaria",'LR',0,'L',$fill);
		$this->Cell(70,7, $comp,'LR',0,'C',$fill);
		$this->Ln();
		//ACAMBIANDO LO COLORES PARA EL PIE DE PAGINA
		$this->SetFillColor(157,152,152);
		$this->SetTextColor(0);
		$this->SetDrawColor(0,0,0);
		$this->SetLineWidth(.3);
		$this->SetFont('Arial','B',12);


		for($i=0;$i<count($footer);$i++)
		$this->Cell(70,7,$footer[$i],1,0,'C',1);

		$this->Cell(120,0,'','L');
	}

/********************************************
*FUBNCIONES PARA VER SI JUSTIFICAMOS EL TEXTO 
*********************************************/
var $flowingBlockAttr;

    function saveFont()
    {

        $saved = array();

        $saved[ 'family' ] = $this->FontFamily;
        $saved[ 'style' ] = $this->FontStyle;
        $saved[ 'sizePt' ] = $this->FontSizePt;
        $saved[ 'size' ] = $this->FontSize;
        $saved[ 'curr' ] =& $this->CurrentFont;

        return $saved;

    }

    function restoreFont( $saved )
    {

        $this->FontFamily = $saved[ 'family' ];
        $this->FontStyle = $saved[ 'style' ];
        $this->FontSizePt = $saved[ 'sizePt' ];
        $this->FontSize = $saved[ 'size' ];
        $this->CurrentFont =& $saved[ 'curr' ];

        if( $this->page > 0)
            $this->_out( sprintf( 'BT /F%d %.2F Tf ET', $this->CurrentFont[ 'i' ], $this->FontSizePt ) );

    }

    function newFlowingBlock( $w, $h, $b = 0, $a = 'J', $f = 0 )
    {

        // cell width in points
        $this->flowingBlockAttr[ 'width' ] = $w * $this->k;

        // line height in user units
        $this->flowingBlockAttr[ 'height' ] = $h;

        $this->flowingBlockAttr[ 'lineCount' ] = 0;

        $this->flowingBlockAttr[ 'border' ] = $b;
        $this->flowingBlockAttr[ 'align' ] = $a;
        $this->flowingBlockAttr[ 'fill' ] = $f;

        $this->flowingBlockAttr[ 'font' ] = array();
        $this->flowingBlockAttr[ 'content' ] = array();
        $this->flowingBlockAttr[ 'contentWidth' ] = 0;

    }

    function finishFlowingBlock()
    {

        $maxWidth =& $this->flowingBlockAttr[ 'width' ];

        $lineHeight =& $this->flowingBlockAttr[ 'height' ];

        $border =& $this->flowingBlockAttr[ 'border' ];
        $align =& $this->flowingBlockAttr[ 'align' ];
        $fill =& $this->flowingBlockAttr[ 'fill' ];

        $content =& $this->flowingBlockAttr[ 'content' ];
        $font =& $this->flowingBlockAttr[ 'font' ];

        // set normal spacing
        $this->_out( sprintf( '%.3F Tw', 0 ) );

        // print out each chunk

        // the amount of space taken up so far in user units
        $usedWidth = 0;

        foreach ( $content as $k => $chunk )
        {

            $b = '';

            if ( is_int( strpos( $border, 'B' ) ) )
                $b .= 'B';

            if ( $k == 0 && is_int( strpos( $border, 'L' ) ) )
                $b .= 'L';

            if ( $k == count( $content ) - 1 && is_int( strpos( $border, 'R' ) ) )
                $b .= 'R';

            $this->restoreFont( $font[ $k ] );

            // if it's the last chunk of this line, move to the next line after
            if ( $k == count( $content ) - 1 )
                $this->Cell( ( $maxWidth / $this->k ) - $usedWidth + 2 * $this->cMargin, $lineHeight, $chunk, $b, 1, $align, $fill );
            else
                $this->Cell( $this->GetStringWidth( $chunk ), $lineHeight, $chunk, $b, 0, $align, $fill );

            $usedWidth += $this->GetStringWidth( $chunk );

        }

    }

    function WriteFlowingBlock( $s )
    {

        // width of all the content so far in points
        $contentWidth =& $this->flowingBlockAttr[ 'contentWidth' ];

        // cell width in points
        $maxWidth =& $this->flowingBlockAttr[ 'width' ];

        $lineCount =& $this->flowingBlockAttr[ 'lineCount' ];

        // line height in user units
        $lineHeight =& $this->flowingBlockAttr[ 'height' ];

        $border =& $this->flowingBlockAttr[ 'border' ];
        $align =& $this->flowingBlockAttr[ 'align' ];
        $fill =& $this->flowingBlockAttr[ 'fill' ];

        $content =& $this->flowingBlockAttr[ 'content' ];
        $font =& $this->flowingBlockAttr[ 'font' ];

        $font[] = $this->saveFont();
        $content[] = '';

        $currContent =& $content[ count( $content ) - 1 ];

        // where the line should be cutoff if it is to be justified
        $cutoffWidth = $contentWidth;

        // for every character in the string
        for ( $i = 0; $i < strlen( $s ); $i++ )
        {

            // extract the current character
            $c = $s[ $i ];

            // get the width of the character in points
            $cw = $this->CurrentFont[ 'cw' ][ $c ] * ( $this->FontSizePt / 1000 );

            if ( $c == ' ' )
            {

                $currContent .= ' ';
                $cutoffWidth = $contentWidth;

                $contentWidth += $cw;

                continue;

            }

            // try adding another char
            if ( $contentWidth + $cw > $maxWidth )
            {

                // won't fit, output what we have
                $lineCount++;

                // contains any content that didn't make it into this print
                $savedContent = '';
                $savedFont = array();

                // first, cut off and save any partial words at the end of the string
                $words = explode( ' ', $currContent );

                // if it looks like we didn't finish any words for this chunk
                if ( count( $words ) == 1 )
                {

                    // save and crop off the content currently on the stack
                    $savedContent = array_pop( $content );
                    $savedFont = array_pop( $font );

                    // trim any trailing spaces off the last bit of content
                    $currContent =& $content[ count( $content ) - 1 ];

                    $currContent = rtrim( $currContent );

                }

                // otherwise, we need to find which bit to cut off
                else
                {

                    $lastContent = '';

                    for ( $w = 0; $w < count( $words ) - 1; $w++)
                        $lastContent .= "{$words[ $w ]} ";

                    $savedContent = $words[ count( $words ) - 1 ];
                    $savedFont = $this->saveFont();

                    // replace the current content with the cropped version
                    $currContent = rtrim( $lastContent );

                }

                // update $contentWidth and $cutoffWidth since they changed with cropping
                $contentWidth = 0;

                foreach ( $content as $k => $chunk )
                {

                    $this->restoreFont( $font[ $k ] );

                    $contentWidth += $this->GetStringWidth( $chunk ) * $this->k;

                }

                $cutoffWidth = $contentWidth;

                // if it's justified, we need to find the char spacing
                if( $align == 'J' )
                {

                    // count how many spaces there are in the entire content string
                    $numSpaces = 0;

                    foreach ( $content as $chunk )
                        $numSpaces += substr_count( $chunk, ' ' );

                    // if there's more than one space, find word spacing in points
                    if ( $numSpaces > 0 )
                        $this->ws = ( $maxWidth - $cutoffWidth ) / $numSpaces;
                    else
                        $this->ws = 0;

                    $this->_out( sprintf( '%.3F Tw', $this->ws ) );

                }

                // otherwise, we want normal spacing
                else
                    $this->_out( sprintf( '%.3F Tw', 0 ) );

                // print out each chunk
                $usedWidth = 0;

                foreach ( $content as $k => $chunk )
                {

                    $this->restoreFont( $font[ $k ] );

                    $stringWidth = $this->GetStringWidth( $chunk ) + ( $this->ws * substr_count( $chunk, ' ' ) / $this->k );

                    // determine which borders should be used
                    $b = '';

                    if ( $lineCount == 1 && is_int( strpos( $border, 'T' ) ) )
                        $b .= 'T';

                    if ( $k == 0 && is_int( strpos( $border, 'L' ) ) )
                        $b .= 'L';

                    if ( $k == count( $content ) - 1 && is_int( strpos( $border, 'R' ) ) )
                        $b .= 'R';

                    // if it's the last chunk of this line, move to the next line after
                    if ( $k == count( $content ) - 1 )
                        $this->Cell( ( $maxWidth / $this->k ) - $usedWidth + 2 * $this->cMargin, $lineHeight, $chunk, $b, 1, $align, $fill );
                    else
                    {

                        $this->Cell( $stringWidth + 2 * $this->cMargin, $lineHeight, $chunk, $b, 0, $align, $fill );
                        $this->x -= 2 * $this->cMargin;

                    }

                    $usedWidth += $stringWidth;

                }

                // move on to the next line, reset variables, tack on saved content and current char
                $this->restoreFont( $savedFont );

                $font = array( $savedFont );
                $content = array( $savedContent . $s[ $i ] );

                $currContent =& $content[ 0 ];

                $contentWidth = $this->GetStringWidth( $currContent ) * $this->k;
                $cutoffWidth = $contentWidth;

            }

            // another character will fit, so add it on
            else
            {

                $contentWidth += $cw;
                $currContent .= $s[ $i ];

            }

        }

    }

}//end of class
?>