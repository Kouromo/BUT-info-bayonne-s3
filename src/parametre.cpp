/**
 * @file génération de paramètres
 * @author tlauriou, jdrsinclair, malves & qrobert
 * @brief 
 * @version 0.1
 * @date 2022-11-10
 * 
 * @copyright Copyright (c) 2022
 * 
 */
#include <iostream>
using namespace std;

int main()
{
    //VARIABLE
    const unsigned short int NB_IMAGE_BASE = 9;
    const unsigned short int NB_POLICE_BASE = 9;
    unsigned short int nbCarac;     // nombre de caractère de la chaine choisi par l'admin
    unsigned short int nbPolice;    // nombre de police à appliquer sur la chaine
    unsigned short int nbEffet;     // nombre d'effet à appliquer sur la chaine
    unsigned short int difficulte;  // le niveau de difficulté des effets que l'ont va appliquer
    unsigned short int policeCarac; // police a appliquer sur une image
    unsigned short int *chaineOrig; // la chaine d'origine qui sera altérer par les effets
    unsigned short int nbImage[NB_POLICE_BASE];    // tableau qui contient le nombre d'image dansz chaque police de caractère que l'on a 
    unsigned short int basePolice[NB_POLICE_BASE];   // tableau contenant toute les polices que l'on va utiliser
    unsigned short int nbTentative;
    unsigned short int tentative = 0;
    
    for (unsigned short int i = 0; i < NB_POLICE_BASE; i++)
    {
        nbImage[i] = NB_IMAGE_BASE;
    }

    // Demander combien de tentative l'utilisateur a droit
    cout << "Combien de tentative voulez-vous pour réussir le captcha ? " << endl;
    cin >> nbTentative;

    //parametrer la génération de la chaîne 
    

    //demander le nombre de caractere à generer
    cout << "Combien de caract"<<'\x8A'<<"re voulez vous dans votre captcha ? " << endl;
    cin >> nbCarac;
    //demander le nombre de police différente polices
    cout << "Combien de police de caract"<<'\x8A'<<"re voulez vous inclure dans votre captcha ? " << endl;
    cin >> nbPolice;
    //demander le nombre d'effet à utiliser
    cout << "Combien d'effet voulez vous dans votre captcha ? " << endl;
    cin >> nbEffet;
    //demander le niveau de difficulte
    cout << "Quelle difficult"<<'\x82'<<"e voulez vous pour votre captcha ? " << endl;
    cin >> difficulte;
    cout << endl;


    //générer la chaine de caractere
    chaineOrig= new short unsigned int[nbCarac];
    //nbImage= new short unsigned int[sizeof basePolice];
    for (unsigned short int i = 0; i < nbCarac; i++)
    {
        //choix de la police aléatoirement
        policeCarac = rand() % nbPolice;

        // choix du caractere aleatoirement
        chaineOrig[i] = rand() % nbImage[policeCarac];
    }
    return 0; 
}
