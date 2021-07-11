<?php

namespace App\Form;

use App\Entity\Ad;
use App\Form\ImageType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;


class AdType extends AbstractType
{
    /**
     *  Permet d'avoir la configuraton de base d'un champ
     * param string $label , string $placeholder, array options
     * retourne un tableau
     */
    private function getConfiguration($label, $placeholder, $options = [])
    {

        return array_merge([
            'label' => $label,
            'attr' => [
                'placeholder' => $placeholder
            ]
        ], $options);
    }
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'title',
                TextType::class,
                $this->getConfiguration('titre', 'Tapez un super titre pour votre annonce')
            )
            ->add(
                'slug',
                TextType::class,
                $this->getConfiguration('Adresse web', 'Tapez votre adressse web (automatique)', [
                    'required' => false
                ])
            )
            ->add('price', MoneyType::class, $this->getConfiguration('Prix du bien', 'Indiquez le prix de vente de votre bien'))
            ->add('introduction', TextType::class, $this->getConfiguration('Introduction', 'Donnez une description globale et rapide de l\'annonce'))
            ->add('content', TextareaType::class, $this->getConfiguration('Description détaillée', 'Tapez une description détaillé de votre annonce'))
            ->add('coverImage', UrlType::class, $this->getConfiguration('Url de l\'image principale', 'Donnez une adresse d\'image qui donne envie'))
            ->add('rooms', IntegerType::class, $this->getConfiguration('Nombre de pièces', 'Indiquez le nombre de pièces disponibles'))
            ->add('latitude', NumberType::class, $this->getConfiguration('Latitude', 'Tapez une latitude (ex 43.6043 pour Toulouse)'))
            ->add('longitude', NumberType::class, $this->getConfiguration('Longitude', 'Tapez une longitude (ex 1.4437 pour Toulouse)'))
            ->add('city', TextType::class, $this->getConfiguration('Ville', 'Tapez le nom de votre ville'))
            ->add('address', TextType::class, $this->getConfiguration('Adresse', 'Tapez l\'adresse'))
            ->add(
                'postalCode',
                IntegerType::class,
                $this->getConfiguration('Code postal', 'Indiquez le code postal')
            )
            ->add('images', CollectionType::class, [
                'entry_type' => ImageType::class,
                'allow_add' => true,
                //pour effacer des images
                'allow_delete' => true
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Ad::class,
        ]);
    }
}
