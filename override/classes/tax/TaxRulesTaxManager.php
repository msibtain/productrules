<?php
/**
 * 2007-2019 PrestaShop and Contributors
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * https://opensource.org/licenses/OSL-3.0
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@prestashop.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade PrestaShop to newer
 * versions in the future. If you wish to customize PrestaShop for your
 * needs please refer to https://www.prestashop.com for more information.
 *
 * @author    PrestaShop SA <contact@prestashop.com>
 * @copyright 2007-2019 PrestaShop SA and Contributors
 * @license   https://opensource.org/licenses/OSL-3.0 Open Software License (OSL 3.0)
 * International Registered Trademark & Property of PrestaShop SA
 */

/**
 * @inheritDoc
 */
class TaxRulesTaxManager extends TaxRulesTaxManagerCore
{
	/**
	 * Return the tax calculator associated to this address.
	 *
	 * @return TaxCalculator
	 */
	public function getTaxCalculator()
	{
		static $tax_enabled = true;
        $account_type = '';
        $tax_id_number = '';

        $id_customer = Context::getContext()->customer->id;

        


        $txtSelectQry = "SELECT `value`  FROM "._DB_PREFIX_."customer_fields 
                        WHERE id_customer = '" . $id_customer . "' AND `field` = 'account_type'";
        $arrData = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS($txtSelectQry);

        if (count($arrData))
        {
            $account_type = $arrData[0]['value'];
        }

        $txtSelectQry = "SELECT `value`  FROM "._DB_PREFIX_."customer_fields 
                        WHERE id_customer = '" . $id_customer . "' AND `field` = 'tax_id_number'";
        $arrData = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS($txtSelectQry);

        if (count($arrData))
        {
            $tax_id_number = $arrData[0]['value'];
        }

        if ($account_type === "business" && $tax_id_number)
        {
            $tax_enabled = false;
        }

		if ( $tax_enabled ) 
        {

            global $cookie;
            if ($cookie->id_lang === 3)
            {
                # language is German;
                $taxGerman = new Tax();
                $taxGerman->id = 98;
                $taxGerman->name = 'Germany Tax 19%';
                $taxGerman->rate = 19;
                $taxGerman->active = true;

                return new TaxCalculator( array($taxGerman) );
            }
            else
            {
                return parent::getTaxCalculator();
            }
            
		}
        else
        {
            //echo $id_customer . " without tacs";
            return new TaxCalculator( array() );
        }
	}
}