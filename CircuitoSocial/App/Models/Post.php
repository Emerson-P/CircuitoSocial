<?php

namespace App\Models;

use MF\Model\Model;

class Post extends Model {
	private $id;
	private $id_usuario;
	private $post;
	private $data;

	public function __get($atributo) {
		return $this->$atributo;
	}

	public function __set($atributo, $valor) {
		$this->$atributo = $valor;
	}

	//salvar
	public function salvar() {

		$query = "insert into posts(id_usuario, post,data)values(:id_usuario, :post, :data)";
		$stmt = $this->db->prepare($query);
		$stmt->bindValue(':id_usuario', $this->__get('id_usuario'));
		$stmt->bindValue(':post', $this->__get('post'));
		$stmt->bindValue(':data', $this->__get('data'));
		$stmt->execute();

		return $this;
	}

	//recuperar
	public function getAll() {

		$query = "
			select 
				t.id, 
				t.id_usuario, 
				u.nome, 
				t.post, 
				DATE_FORMAT(t.data, '%d/%m/%Y %H:%i') as data
			from 
				posts as t
				left join usuarios as u on (t.id_usuario = u.id)
			where 
				t.id_usuario = :id_usuario
				or t.id_usuario in (select id_usuario_seguindo from usuarios_seguidores where id_usuario = :id_usuario)
			order by
				t.data desc
		";

		$stmt = $this->db->prepare($query);
		$stmt->bindValue(':id_usuario', $this->__get('id_usuario'));
		$stmt->execute();

		return $stmt->fetchAll(\PDO::FETCH_ASSOC);
	}
}