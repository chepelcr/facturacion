<?php

/**
 * Descripción: Controlador para la entidad Rol
 */

namespace App\Controllers;

use App\Services\AutenticacionService;
use App\Services\TaxpayersService;

/**
 * Controlador para el modulo de configuracion
 * @package App\Controllers
 * @subpackage Configuracion
 * @version 2.0
 * @autor jcampos
 */
class Configuracion extends BaseController {

    protected $isModulo = true;

    protected $nombreModulo = 'configuracion';

    protected $objetos = ['empresa', 'documentos'];

    /** Devolver el dash de la aplicacion */
    public function index() {
        if (is_login()) {
            $script = cargar("cargar_inicio_modulo('configuracion', 'Configuración');");

            $data = array(
                'script' => $script,
            );

            return $this->inicio($data);
        } //Fin de la validacion

        else {
            header('Location: ' . baseUrl('login'));
        }
    } //Fin de la funcion index

    /**
     * Entrar a la configuracion del modulo de administracion
     */
    public function empresa() {
        if (is_login()) {
            if (validar_permiso("configuracion", "empresa", "consultar")) {
                if (getSegment(3) == "listado") {
                    $taxpayersService = new TaxpayersService();

                    $formData = array(
                        'taxpayer' => $taxpayersService->getEmpresaData()
                    );

                    return view('seguridad/configuracion/empresa', $formData);
                } else {
                    $script = cargar("cargar_listado('configuracion', 'empresa', 'Configuración', 'Empresa', ' " . baseUrl("configuracion/empresa/listado") . "');");

                    $data = array(
                        'script' => $script
                    );

                    return $this->inicio($data);
                }
            } else {
                $data = array(
                    'error' => 'No tiene permisos para acceder a la página.',
                    'status'=> 403
                );

                return $this->error($data);
            }
        } else {
            return $this->inicio();
        }
    } //Fin de la función empresa

    /**
     * Entrar a la configuracion del modulo de facturacion
     */
    public function documentos() {
        if (is_login()) {
            if (validar_permiso("configuracion", "documentos", "consultar")) {
                if (getSegment(3) == "listado") {
                    $autenticationService = new AutenticacionService();

                    $configuraciones = $autenticationService->obtenerConfiguracionesPorIdContribuyente(getTaxpayerId());

                    if (isset($configuraciones->error)) {
                        $data = array(
                            'error' => $configuraciones->error,
                            'codigo' => $configuraciones->status
                        );

                        return $this->error($data);
                    } else {
                        return view('seguridad/configuracion/facturacion', $configuraciones);
                    }
                } else {
                    $script = cargar("cargar_listado('configuracion', 'documentos', 'Configuración', 'Documentos', ' " . baseUrl("configuracion/documentos/listado") . "');");

                    $data = array(
                        'script' => $script
                    );

                    return $this->inicio($data);
                }
            } else {
                if (getSegment(3) == "listado") {
                    $error = array(
                        'error' => 'No tiene permisos para acceder a la pagina.',
                        'codigo' => 403
                    );

                    return $this->error($error);
                } else {
                    $error = $this->object_error(500, 'No tiene permisos para consultar documentos.');

                    return $this->error($error);
                }
            }
        } else {
            header(self::LOCATION . baseUrl('login'));
        }
    } //Fin del metodo para entrar a la configuracion del modulo de facturacion

    /**
     * Actualizar la configuracion de la empresa
     */
    public function update($objeto, $data) {
        if (is_login()) {
            if ($objeto == 'hacienda' && validar_permiso('configuracion', 'documentos', 'modificar')) {
                $autenticationService = new AutenticacionService();

                $response = $autenticationService->actualizarConfiguracionesPorIdContribuyente(getTaxpayerId(), $data);

                if (isset($response->error)) {
                    $error = array(
                        'error' => $response->error,
                        'codigo' => $response->status
                    );

                    return $this->error($error);
                } else {
                    return json_encode($response);
                }
            } elseif ($objeto == 'llave_criptográfica' && validar_permiso('configuracion', 'documentos', 'modificar')) {
                if (isset($_FILES['file_content'])) {

                    $data['file_content'] = $_FILES['file_content'];
                }

                $autenticationService = new AutenticacionService();

                $response = $autenticationService->validarLlaveCriptografica(getTaxpayerId(), $data);

                if (isset($response->error)) {
                    $error = array(
                        'error' => $response->error,
                        'codigo' => $response->status
                    );

                    return $this->error($error);
                } else {
                    return json_encode($response);
                }
            } else {
                $error = $this->object_error(500, 'No se ha enviado la información correcta para actualizar la configuración.');

                return $this->error($error);
            } //Fin de la validacion de permisos
        } else {
            header(self::LOCATION . baseUrl('login'));
        } //Fin de la validacion de login
    } //Fin del metodo para actualizar la configuracion de la empresa
} //Fin de la clase
