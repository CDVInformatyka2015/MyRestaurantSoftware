<?php

namespace RestApiBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Invoices
 *
 * @ORM\Table(name="invoices")
 * @ORM\Entity(repositoryClass="RestApiBundle\Repository\InvoicesRepository")
 */
class Invoices
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="invoice", type="text")
     *
     * @Assert\NotNull()
     * @Assert\All({
     *     @Assert\NotBlank,
     *     @Assert\Type("string")
     * })
     */
    private $invoice;

    /**
     * @var int
     *
     * @ORM\Column(name="tableNumber", type="integer")
     *
     * @Assert\NotNull()
     * @Assert\Type("integer")
     * @Assert\Range(min="1")
     */
    private $tableNumber;

    /**
     * @var int
     *
     * Status ID:
     * 0 - New invoice
     * 1 - Ready
     * 2 - On the table
     * 3 - Closed
     *
     * @ORM\Column(name="status", type="integer")
     *
     * @Assert\Range(min="0", max="3")
     */
    private $status = 0;

    /**
     * @var bool
     *
     * @ORM\Column(name="delivery", type="boolean")
     *
     * @Assert\NotNull()
     * @Assert\Type("boolean")
     */
    private $delivery = true;


    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set invoice
     *
     * @param array $invoice
     *
     * @return Invoices
     */
    public function setInvoice($invoice)
    {
        $this->invoice = $invoice;

        return $this;
    }

    /**
     * Get invoice
     *
     * @return array
     */
    public function getInvoice()
    {
        return $this->invoice;
    }

    /**
     * Set tableNumber
     *
     * @param integer $tableNumber
     *
     * @return Invoices
     */
    public function setTableNumber($tableNumber)
    {
        $this->tableNumber = $tableNumber;

        return $this;
    }

    /**
     * Get tableNumber
     *
     * @return int
     */
    public function getTableNumber()
    {
        return $this->tableNumber;
    }

    /**
     * Set status
     *
     * @param integer $status
     *
     * @return Invoices
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get status
     *
     * @return int
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Set delivery
     *
     * @param boolean $delivery
     *
     * @return Invoices
     */
    public function setDelivery($delivery)
    {
        $this->delivery = $delivery;

        return $this;
    }

    /**
     * Get delivery
     *
     * @return bool
     */
    public function getDelivery()
    {
        return $this->delivery;
    }
}

