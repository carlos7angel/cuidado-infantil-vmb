<style>
    html,body { padding:0; margin:0; font-family: Inter, Helvetica, "sans-serif"; } a:hover { color: #009ef7; } p {margin: 0; }
</style>
<div id="#kt_app_body_content" style="background-color:#D5D9E2; font-family:Arial,Helvetica,sans-serif; line-height: 1.5; min-height: 100%; font-weight: normal; font-size: 15px; color: #2F3044; margin:0; padding:50px 0; width:100%;">

    <div style="background-color:#ffffff; padding: 45px 0 34px 0; border-radius: 24px; margin:40px auto; max-width: 600px;">
        <table align="center" border="0" cellpadding="0" cellspacing="0" width="100%" height="auto" style="border-collapse:collapse" aria-describedby="table"><!-- //NOSONAR -->
            <tbody>
            <tr>
                <td align="center" valign="center" style="text-align:center; padding-bottom: 10px">
                    <div style="margin:0 60px 34px 60px">
                        <div style="text-align:start; font-size: 13px; font-weight: 500; margin-bottom: 15px; font-family:Arial,Helvetica,sans-serif;">
                            <p style="margin-top:0; margin-bottom:9px; color:#181C32; font-size: 18px; font-weight:600">Estimada/o, {{ $user->name }}</p>
                            <p style="margin-top:0; margin-bottom:2px; color:#5E6278">Su cuenta de Educador ha sido creada y habilitada para su acceso.</p>
                            <p style="margin-top:0; margin-bottom:2px; color:#5E6278">Aplicación móvil de
                                <span style="color:#883f7e; font-weight: 600">Seguimiento y Monitoreo de Centros de Cuidado Infantil</span>.
                            </p>
                            <p style="margin-top:0; color:#5E6278; margin-bottom:13px">A continuación, encontrará sus datos de acceso, se le recomienda cambiar la contraseña una vez ingresado al sistema.</p>
                        </div>
                        <div style="text-align:start; background: #F9F9F9; border-radius: 12px; padding:15px 30px">
                            <p style="margin-top:0; margin-bottom:0px; color:#181C32; font-size: 14px; font-weight:700">Usuario: {!! $user->email !!}</p>
                            <p style="margin-top:0; margin-bottom:0px; color:#181C32; font-size: 14px; font-weight:700">Contraseña: {!! $password !!}</p>   
                            <p style="margin-top:0; margin-bottom:0px; color:#181C32; font-size: 14px; font-weight:700">URL Servidor: {!! $url !!}</p>
                        </div>
                    </div>
                </td>
            <tr>
                <td align="center" valign="center" style="font-size: 13px; text-align:center; padding: 0 10px 10px 10px; font-weight: 500; color: #A1A5B7; font-family:Arial,Helvetica,sans-serif">
                    <p style="margin-top:0; color:#2c2d35; font-size: 14px; font-weight: 600; margin-bottom:8px">Visión Mundial Bolivia</p>
                    <p style="margin-top:0; margin-bottom:2px">La Paz Av. Hernando Siles N° 6023, esq. Calle 15 de Obrajes</p>
                    <p style="margin-top:0; margin-bottom:2px">(+591) 2 2165900</p>
                    <p style="margin-top:0; margin-bottom:0;">bolivia@wvi.org</p>
                </td>
            </tr>
            <tr>
                <td align="center" valign="center" style="font-size: 12px; margin-top: 10px; padding:0 15px; text-align:center; font-weight: 500; color: #A1A5B7;font-family:Arial,Helvetica,sans-serif">
                    <p style="margin: 0">No responder a este mensaje, es una notificación automática.</p>
                </td>
            </tr>
            </tbody>
        </table>
    </div>
</div>