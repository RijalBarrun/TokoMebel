<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AttributeOptionRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $attributeID = (int) $this->get('attribute_id');
        $id = (int) $this->get('id');

        if ($this->method() == 'PUT') {
            $name = 'required|unique:attribute_options,name,'.$id.',id,attribute_id,'.$attributeID;
        } else {
            $name = 'required|unique:attribute_options,name,NULL,id,attribute_id,'.$attributeID;
        }

        return [
            'name' => $name,
        ];
    }
 public function options($attributeID)
    {
        if (empty($attributeID)) {
            return redirect('admin/attributes');
        }

        $attribute = Attribute::findOrFail($attributeID);
        $this->data['attribute'] = $attribute;

        return view('admin.attributes.options', $this->data);
    }

    public function store_option(AttributeOptionRequest $request, $attributeID)
    {
        if (empty($attributeID)) {
            return redirect('admin/attributes');
        }
        
        $params = [
            'attribute_id' => $attributeID,
            'name' => $request->get('name'),
        ];

        if (AttributeOption::create($params)) {
            Session::flash('success', 'option has been saved');
        }

        return redirect('admin/attributes/'. $attributeID .'/options');
    }

    public function edit_option($optionID)
    {
        $option = AttributeOption::findOrFail($optionID);

        $this->data['attributeOption'] = $option;
        $this->data['attribute'] = $option->attribute;

        return view('admin.attributes.options', $this->data);
    }

    public function update_option(AttributeOptionRequest $request, $optionID)
    {
        $option = AttributeOption::findOrFail($optionID);
        $params = $request->except('_token');

        if ($option->update($params)) {
            Session::flash('success', 'Option has been updated');
        }

        return redirect('admin/attributes/'. $option->attribute->id .'/options');
    }

    public function remove_option($optionID)
    {
        if (empty($optionID)) {
            return redirect('admin/attributes');
        }

        $option = AttributeOption::findOrFail($optionID);

        if ($option->delete()) {
            Session::flash('success', 'option has been deleted');
        }

        return redirect('admin/attributes/'. $option->attribute->id .'/options');
    }
}

