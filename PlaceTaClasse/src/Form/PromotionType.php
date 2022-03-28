<?php

namespace App\Form;

use App\Entity\Module;
use App\Entity\Promotion;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionResolver\OptionResolver;
use Symfony\Component\OptionsResolver\OptionsResolver;


class PromotionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nomLong')
            ->add('nomCourt')
            
           
        ;

        echo"liste d'étudiants(optionnel) : <input type=\"file\" id=\"upload-csv\" accept=\".csv\" >
        <input type=\"button\" id=\"btn-upload-csv\" value=\"valider\"/>";


       echo" <script type='text/javascript'>

	let btn_upload = document.getElementById('btn-upload-csv').addEventListener('click', ()=> {

        console.log(document.getElementById('upload-csv').value);
		Papa.parse(document.getElementById('upload-csv').files[0], {
			download: true,
			header: true,
			complete: function(results) {
				console.log(results);
                
             }
         })
     });

    
    </script>";


    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Promotion::class,
        ]);
    }
}
