<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\ServiceStateModel;

class ServiceStates extends BaseController
{
  public function index()
  {
    $model = new ServiceStateModel();
    $data['states'] = $model->findAll();
    return $this->loadView('admin/service_states', $data);
  }

  public function save()
  {
    $model = new ServiceStateModel();
    $id = $this->request->getPost('id');
    $data = [
      'state_name' => $this->request->getPost('state_name'),
      'status'     => $this->request->getPost('status'),
    ];

    if ($id) {
      $model->update($id, $data);
      return redirect()->back()->with('success', 'State updated successfully');
    } else {
      $model->insert($data);
      return redirect()->back()->with('success', 'State added successfully');
    }
  }

  public function delete($id)
  {
    $model = new ServiceStateModel();
    $model->delete($id);
    return redirect()->back()->with('success', 'State deleted successfully');
  }
}
