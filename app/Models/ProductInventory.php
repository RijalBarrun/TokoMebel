<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductInventory extends Model
{
	protected $fillable = [
		'product_id',
		'qty',
	];

	/**
	 * Define relationship with the Product
	 *
	 * @return void
	 */
	public function product()
	{
		return $this->belongsTo('App\Models\Product');
	}

	/**
	 * Reduce stock product
	 *
	 * @param int $productId product ID
	 * @param int $qty       qty product
	 *
	 * @return void
	 */
	public static function reduceStock($productId, $qty)
	{
		$inventory = self::where('product_id', $productId)->firstOrFail();
		$inventory->qty = $inventory->qty - $qty;
		$inventory->save();
	}
}
