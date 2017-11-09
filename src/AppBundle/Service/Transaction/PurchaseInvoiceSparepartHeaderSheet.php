<?php

namespace AppBundle\Service\Transaction;

use Symfony\Component\Validator\Validator\ValidatorInterface;
use LibBundle\Excel\PhpExcelObjectParser;
use LibBundle\Doctrine\EntityRepository;

class PurchaseInvoiceSparepartHeaderSheet
{
    private $validator;
    private $parser;
    private $purchaseInvoiceSparepartHeaderRepository;
    
    public function __construct(ValidatorInterface $validator, PhpExcelObjectParser $parser, EntityRepository $purchaseInvoiceSparepartHeaderRepository)
    {
        $this->validator = $validator;
        $this->parser = $parser;
        $this->purchaseInvoiceSparepartHeaderRepository = $purchaseInvoiceSparepartHeaderRepository;
    }
    
    public function parse($headerDataFile, $headerMappingXml, $detailDataFile, $detailMappingXml)
    {
        $numberFormatter = function($value) { return number_format(doubleval(str_replace(',', '.', $value)), 2, '.', ''); };
        $dateFormatter = function($value) { return date_create_from_format('Y-m-d H:i:s', $value); };
        
        $this->parser->setObjectMappingsFromXml($headerMappingXml);
        $this->parser->addFormatter('Number', $numberFormatter);
        $this->parser->addFormatter('Date', $dateFormatter);
        $headerData = $this->parser->load($headerDataFile);
        
        $this->parser->setObjectMappingsFromXml($detailMappingXml);
        $this->parser->addFormatter('Number', $numberFormatter);
        $detailData = $this->parser->load($detailDataFile);
        
        foreach ($headerData['references']['invoiceNumber'] as $headerInvoiceNumber => $headerIndexes) {
            foreach ($headerIndexes as $headerIndex) {
                $headerObject = $headerData['objects'][$headerIndex];
                foreach ($detailData['references']['invoiceNumber'] as $detailInvoiceNumber => $detailIndexes) {
                    foreach ($detailIndexes as $detailIndex) {
                        if ($headerInvoiceNumber === $detailInvoiceNumber) {
                            $details = $headerObject->getPurchaseInvoiceSparepartDetails();
                            $detailObject = $detailData['objects'][$detailIndex];
                            $detailObject->setPurchaseInvoiceSparepartHeader($headerObject);
                            $details->add($detailObject);
                        }
                    }
                }
            }
        }
        
        return $headerData['objects'];
    }
    
    public function validate(array $purchaseInvoiceSparepartHeaders)
    {
        if (empty($purchaseInvoiceSparepartHeaders)) {
            return false;
        }
        foreach ($purchaseInvoiceSparepartHeaders as $purchaseInvoiceSparepartHeader) {
            $errors = $this->validator->validate($purchaseInvoiceSparepartHeader);
            if (count($errors) > 0) {
                return false;
            }
            foreach ($purchaseInvoiceSparepartHeader->getPurchaseInvoiceSparepartDetails() as $purchaseInvoiceSparepartDetail) {
                $errors = $this->validator->validate($purchaseInvoiceSparepartDetail);
                if (count($errors) > 0) {
                    return false;
                }
            }
        }
        
        return true;
    }
    
    public function save(array $purchaseInvoiceSparepartHeaders)
    {
        $this->purchaseInvoiceSparepartHeaderRepository->add($purchaseInvoiceSparepartHeaders, array(
            'purchaseInvoiceSparepartDetails' => array('add' => true),
        ));
    }
}
