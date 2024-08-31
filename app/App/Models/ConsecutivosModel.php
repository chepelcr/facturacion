<?php
    namespace App\Models;

use Core\Model;

/**Manejar el consecutivo de los documentos */
class ConsecutivosModel extends Model
{
    protected $tableName = 'consecutivos';
    protected $primaryKey = 'id_consecutivo';

    protected $tableFields = [
        'ambiente',
        'tipo_documento',
        'consecutivo'
    ];

    

	protected $autoIncrement = true;

	protected $auditorias = true;

    /**Obtener el consecutivo de facturacion */
    public function obtener_consecutivo($tipo_documento, $ambiente)
    {
        $this->where('tipo_documento', $tipo_documento)->where('ambiente', $ambiente);

        return $this->fila();
    }//Fin de la función obtener_consecutivo

    public function actualizar_consecutivo($id_consecutivo, $consecutivo)
    {
        $data = array(
            'consecutivo' => $consecutivo
        );

        return $this->update($data, $id_consecutivo);
    }//Fin de la función actualizar_consecutivo
}//Fin de la clase