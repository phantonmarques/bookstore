<?php

namespace App\Entity;

use App\Repository\LivroRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: LivroRepository::class)]
#[ORM\Table(name: '`Livro`')]
class Livro
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(name: '`Codl`')]
    private ?int $id = null;

    #[ORM\Column(name: '`Titulo`', length: 40)]
    private ?string $titulo = null;

    #[ORM\Column(name: '`Editora`', length: 40)]
    private ?string $editora = null;

    #[ORM\Column(name: '`Edicao`')]
    private ?int $edicao = null;

    #[ORM\Column(name: '`AnoPublicacao`', length: 4)]
    private ?string $anoPublicacao = null;

    #[ORM\Column(name: '`Preco`', type: 'decimal', precision: 10, scale: 2)]
    private ?string $preco = null;

    /**
     * @var Collection<int, Assunto>
     */
    #[ORM\ManyToMany(targetEntity: Assunto::class, inversedBy: 'livros')]
    #[ORM\JoinTable(name: '`Livro_Assunto`')]
    #[ORM\JoinColumn(name: '`Livro_Codl`', referencedColumnName: '`Codl`')]
    #[ORM\InverseJoinColumn(name: '`Assunto_codAs`', referencedColumnName: '`codAs`')]
    private Collection $assuntos;

    /**
     * @var Collection<int, Autor>
     */
    #[ORM\ManyToMany(targetEntity: Autor::class, inversedBy: 'livros')]
    #[ORM\JoinTable(name: '`Livro_Autor`')]
    #[ORM\JoinColumn(name: '`Livro_Codl`', referencedColumnName: '`Codl`')]
    #[ORM\InverseJoinColumn(name: '`Autor_CodAu`', referencedColumnName: '`CodAu`')]
    private Collection $autores;

    public function __construct()
    {
        $this->assuntos = new ArrayCollection();
        $this->autores = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitulo(): ?string
    {
        return $this->titulo;
    }

    public function setTitulo(string $titulo): static
    {
        $this->titulo = $titulo;

        return $this;
    }

    public function getEditora(): ?string
    {
        return $this->editora;
    }

    public function setEditora(string $editora): static
    {
        $this->editora = $editora;

        return $this;
    }

    public function getEdicao(): ?int
    {
        return $this->edicao;
    }

    public function setEdicao(?int $edicao): static
    {
        $this->edicao = $edicao;

        return $this;
    }

    public function getAnoPublicacao(): ?string
    {
        return $this->anoPublicacao;
    }

    public function setAnoPublicacao(string $anoPublicacao): static
    {
        $this->anoPublicacao = $anoPublicacao;

        return $this;
    }

    public function getPreco(): ?string
    {
        return $this->preco;
    }

    public function setPreco(?string $preco): static
    {
        $this->preco = $preco;

        return $this;
    }

    /**
     * @return Collection<int, Assunto>
     */
    public function getAssuntos(): Collection
    {
        return $this->assuntos;
    }

    public function addAssunto(Assunto $assunto): static
    {
        if (!$this->assuntos->contains($assunto)) {
            $this->assuntos->add($assunto);
        }

        return $this;
    }

    public function removeAssunto(Assunto $assunto): static
    {
        $this->assuntos->removeElement($assunto);

        return $this;
    }

    /**
     * @return Collection<int, Autor>
     */
    public function getAutores(): Collection
    {
        return $this->autores;
    }

    public function addAutor(Autor $autor): static
    {
        if (!$this->autores->contains($autor)) {
            $this->autores->add($autor);
        }

        return $this;
    }

    public function removeAutor(Autor $autor): static
    {
        $this->autores->removeElement($autor);

        return $this;
    }
}
