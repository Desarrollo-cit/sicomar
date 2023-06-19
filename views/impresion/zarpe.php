<p style="text-align: center; line-height: -1; font-weight:bold" ><?= $user['dep_desc_lg'] ?></p>
<p style="text-align: center; line-height: -1;" >Ejército de Guatemala, Marina de la Defensa Nacional</p>
<?php if($user['dep_llave'] == '2940'): ?>
    <p style="text-align: center; line-height: -1;" >Ciudad de Guatemala - Republica de Guatemala, C.A.</p>
<?php elseif ($user['dep_llave'] == '2940'): ?>
    <p style="text-align: center; line-height: -1;" >Santo Tomás de Castilla, Izabal-Republica de Guatemala, C.A.</p>
<?php endif ?>
<br>
<p style="text-align: center; line-height: -1; font-weight:bold; text-decoration:underline" >RR/HZ-COFEN-O-0008-MASV/fmao-2021</p>
<br>
<table>
    <tr>
        <td>De:</td>
        <td>COMANDANTE DEL <?= $user['dep_desc_lg'] ?></td>
    </tr>
    <tr>
        <td>A:</td>
        <td>COMANDANTE DE ESCUADRA TÁCTICA / FLOTA NAVAL</td>
    </tr>
</table>
<br>
<table style="margin-left: 100px">
    <tr>
        <td >ORGANIZACIÓN:</td>
        <td>nombre de la embaración</td>
    </tr>
    <tr>
        <td >TRIPULACIÓN:</td>
        <td>tripulante</td>
    </tr>
    <tr>
        <td ></td>
        <td>tripulante</td>
    </tr>
    <tr>
        <td ></td>
        <td>tripulante</td>
    </tr>
    <tr>
        <td ></td>
        <td>tripulante</td>
    </tr>
    <tr>
        <td ></td>
        <td>tripulante</td>
    </tr>

</table>
<ol type="I" style="font-weight: bold;">
    <li> 
        <span >SITUACIÓN:</span> 
        <br>
        <p style="text-align: justify; font-weight:unset"><?= htmlspecialchars_decode($operacion['ope_situacion']) ?></p>
    </li>
    <li> 
        <span >MISIÓN:</span> 
        <br>
        <p style="text-align: justify; font-weight:unset">Lorem ipsum dolor, sit amet consectetur adipisicing elit. Voluptatem corporis harum illo at earum quam error dolore eaque non possimus maxime quibusdam, incidunt quae excepturi officiis necessitatibus. Fugiat, minus maiores!</p>
    </li>
    <li> 
        <span >EJECUCIÓN:</span> 
        <br>
        <ol style="font-weight:unset" type="A">
            <li>
                <span  >CONCEPTO DE LA OPERACIÓN:</span> 
                <br>
                <p style="text-align: justify; font-weight:unset">Lorem ipsum dolor, sit amet consectetur adipisicing elit. Voluptate sapiente aut, delectus doloremque totam dicta reiciendis consequatur accusantium ullam nesciunt explicabo dolore recusandae labore vel ipsa eius nulla debitis facilis.</p>
            </li>
        </ol>

    </li>
    <li> 
        <span >ADMINISTRACIÓN Y LOGISTICA:</span> 
        <br>
        <p style="text-align: justify; font-weight:unset">De acuerdo al P.A.N.</p>
    </li>     
    <li> 
        <span >COMANDO Y TRANSMISIONES:</span> 
        <br>
        <ol style="font-weight:unset" type="A">
            <li>
                <span  >COMANDO</span> 
                <ol  type="1">
                    <li>
                        <p style="text-align: justify; font-weight:unset">El puesto de mando principal se ubicará en el Comando de Fuerza Especial Naval.</p>
                    </li>
                </ol>
            </li>
            <li>
                <span  >TRANSMISIONES</span> 
                <ol  type="1">
                    <li>
                        <p style="text-align: justify; font-weight:unset">I.O.T. en vigencia</p>
                        <p style="text-align: justify; font-weight:unset">Medios de transmisiones de cada unidad.</p>
                        <p style="text-align: justify; font-weight:unset">Red de telefonía nacional.</p>
                    </li>
                </ol>
            </li>
        </ol>
    </li>     
</ol>

<p align="center">Puerto Quetzal, Escuintla <?= strftime("%d de %B de %Y ") ?></p>
<p align="center"><?= formatearGrado($firmas[0]['grado'], $firmas[0]['gra_codigo'], $firmas[0]['arma'], $firmas[0]['arm_codigo'] ) ?></p>
<br>
<p align="center" style="font-weight:bold"><?= $firmas[0]['nombre'] ?></p>

       

