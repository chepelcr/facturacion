<?php

/**
 * Descripción: Controlador para la entidad libro
 */

namespace App\Controllers;

use App\Models\DepartamentosModel;
use App\Models\FormatosModel;
use App\Models\TiendasModel;

/**
 * Clase para manejar el modulo de walmart
 * @package App\Controllers
 * @subpackage Walmart
 * @version 1.0.0
 * @author jcampos
 */
class Walmart extends BaseController
{
    protected $isModulo = true;

    protected $nombreModulo = 'walmart';

    protected $objetos = ['tiendas', 'departamentos', 'ordenes'];

    protected $validationFields = array(
        'tiendas' => 'gln',
        'departamentos' => 'id_proveedor',
        'ordenes' => 'numero_orden'
    );

    protected $validacion_login = array(
        'tiendas' => true,
        'departamentos' => true,
        'ordenes' => true
    );

    /**Cargar el inicio del modulo walmart */
    public function index()
    {
        if (is_login()) {
            $script = cargar('cargar_inicio_modulo("walmart");');

            $data = array(
                'script' => $script,
            );

            return $this->inicio($data);
        } //Fin de la validacion

        else
            header('Location: ' . baseUrl('login'));
    }

    /**Cargar el submodulo de tiendas */
    public function tiendas()
    {
        if (is_login()) {
            if (validar_permiso('walmart', 'tiendas', 'consultar')) {
                switch (getSegment(3)) {
                    case 'listado':
                        $tiendasModel = new TiendasModel();

                        $estado = 'all';

                        if (post('id_estado') == 'inactivos') {
                            $tiendas = $tiendasModel->obtener(post('id_estado'));
                            $estado = 'inactivos';
                        } else {
                            if (post('id_estado') == 'activos') {
                                $estado = 'activos';
                                $tiendas = $tiendasModel->obtener('activos');
                            } else {
                                $tiendas = $tiendasModel->obtener('all');
                            }
                        }

                        $tableName = 'walmart/tiendas/table';

                        $data_tabla = array(
                            'nombreTable' => $tableName,
                            'nombre_tabla' => 'listado_walmart_tiendas',

                            'dataTable' => array(
                                'tiendas' => $tiendas,
                            ),
                            'id_estado' => $estado
                        );

                        $formatosModel = new FormatosModel();
                        $formatos = $formatosModel->obtener('all');

                        $nombreForm = 'walmart/tiendas/form';

                        $data_form = array(
                            'dataForm' => array(
                                'formatos' => $formatos,
                            ),
                            'nombreForm' => $nombreForm,
                            'nombre_form' => 'frm_walmart_tiendas'
                        );

                        $data = array(
                            'data_tabla' => $data_tabla,
                            'data_form' => $data_form,
                        );

                        return $this->listado($data);
                        break;

                    default:
                        $script = cargar('cargar_listado("walmart", "tiendas", "Walmart", "Puntos de venta", "' . baseUrl('walmart/tiendas/listado') . '");');

                        $data = array(
                            'script' => $script
                        );

                        return $this->inicio($data);
                        break;
                }
            } else {
                $error = $this->object_error(403, 'No tiene permiso para realizar esta accion.');

                return $this->error($error);
            }
        } //Fin de la validacion

        else
            header('Location: ' . baseUrl('login'));
    } //Fin de la funcion para mostrar el submodulo de tiendas

    /**Cargar el submodulo de departamentos */
    public function departamentos()
    {
        if (is_login()) {
            if (validar_permiso('walmart', 'departamentos', 'consultar')) {
                switch (getSegment(3)) {
                    case 'listado':
                        $departamentosModel = new DepartamentosModel();

                        $estado = 'all';

                        if (post('id_estado') == 'inactivos') {
                            $departamentos = $departamentosModel->obtener(post('id_estado'));
                            $estado = 'inactivos';
                        } else {
                            if (post('id_estado') == 'activos') {
                                $estado = 'activos';
                                $departamentos = $departamentosModel->obtener('activos');
                            } else {
                                $departamentos = $departamentosModel->obtener('all');
                            }
                        }

                        $tableName = 'walmart/departamentos/table';

                        $data_tabla = array(
                            'nombreTable' => $tableName,
                            'nombre_tabla' => 'listado_walmart_departamentos',

                            'dataTable' => array(
                                'departamentos' => $departamentos,
                            ),
                            'id_estado' => $estado
                        );

                        $nombreForm = 'walmart/departamentos/form';

                        $data_form = array(
                            'nombreForm' => $nombreForm,
                            'nombre_form' => 'frm_walmart_departamentos'
                        );

                        $data = array(
                            'data_tabla' => $data_tabla,
                            'data_form' => $data_form,
                        );

                        return $this->listado($data);
                        break;

                    default:
                        $script = cargar('cargar_listado("walmart", "departamentos", "Walmart", "Departamentos", "' . baseUrl('walmart/departamentos/listado') . '");');

                        $data = array(
                            'script' => $script
                        );

                        return $this->inicio($data);
                        break;
                }
            } else {
                $error = $this->object_error(403, 'No tiene permiso para realizar esta accion.');

                return $this->error($error);
            }
        } //Fin de la validacion

        else
            header('Location: ' . baseUrl('login'));
    } //Fin de la funcion para mostrar el submodulo de departamentos
}//Fin de la clase
