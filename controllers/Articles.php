<?php
    namespace controllers; 

    class Articles extends \app\Controller{ 

        /** Cette méthode affiche la liste des articles *
         * @return void */ 
        
         public function index(): void { 
            // On instancie le modèle "Articles"
            $this->loadModel('Articles');
            
            // On stocke la liste des articles dans $articles
            $articles = $this->Articles->getAll();
            
            // On aenvoie les données à la vue index
            $this->render('index', compact('articles')); 
        }
        
        /** * Méthode permettant d'afficher un article à partir de son slug *
         *  @param string $slug 
         * @return void */ 

        public function lire(string $slug){ 
            $this->loadModel('Articles'); 
            $articles = $this->Articles->findBySlug($slug); 
            $this->render('lire', compact('articles')); 
        }

        public function create(): void {
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $this->loadModel('Articles');
                $message = $this->Articles->create($_POST);
                echo $message;
            } else {
                $this->render('create');
            }
        }

        public function update(int $id): void {
            $this->loadModel('Articles');
            $this->loadModel('Categories');
            
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $message = $this->Articles->update($id, $_POST);

                // Préparer les données pour la vue
                $bo = [
                    'msg' => $message,
                    'articles' => $this->Articles->getAll(),
                    'categories' => $this->Categories->getAll()
                ];

                // Renvoyez à la vue Articles
                $this->render('Articles', compact('bo'));
                
            } else {
                $article = $this->Articles->getById($id);
                $categories = $this->Categories->getAll();

                $bo = [
                    'article' => $article,
                    'categories' => $categories
                ];

                $this->render('update', compact('bo'));
            }
        }

        public function delete(int $id): void {
            $this->loadModel('Articles');
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $message = $this->Articles->delete($id);
                echo $message;
            } else {
                $article = $this->Articles->getById($id);
                $this->render('delete', compact('article'));
            }
        }
        
    }
?>