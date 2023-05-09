# Fukuoka Restaurant Crawler Backend

> 이 프로젝트는 해당 레스토랑 정보를 일본 후쿠오카의 Tabelog로부터 크롤링하여 사용자가 사용하기 쉬운 UI로 레스토랑을 둘러볼 수 있도록 제공하는 웹 서비스의 백엔드 부분입니다. 백엔드는 Laravel 프레임워크를 사용하고 있습니다.

## 요구 사항
- PHP >= 7.3
- Composer
- Laravel 8.x

## 설치 및 설정
1. 레포지토리를 복제합니다.
  ```git
  git clone https://github.com/project-TEAM-5/team-5-backend.git
  ```

2. 프로젝트 디렉토리로 이동합니다.
  ```
  cd fukuoka-restaurant-crawler-backend
  ```

3. Composer를 사용하여 필요한 패키지를 설치합니다.
  ```
  composer install
  ```

4. .env 파일을 생성하고 설정을 적용합니다.
  ```
  cp .env.example .env
  ```

5. APP_KEY를 생성합니다.
  ```
  php artisan key:generate
  ```

6. 데이터베이스 설정을 .env 파일에 입력합니다.
  ```
  DB_CONNECTION=mysql
  DB_HOST=127.0.0.1
  DB_PORT=3306
  DB_DATABASE=your_database_name
  DB_USERNAME=your_username
  DB_PASSWORD=your_password
  ```

7. 테이블 및 데이터를 생성합니다.
  ```
  php artisan migrate --seed
  ```

8. 특정 시간 간격으로 데이터를 크롤링하기 위해 Laravel 스케쥴러를 설정합니다.
```
* * * * * cd /path-to-your-project && php artisan schedule:run >> /dev/null 2>&1
```

9. 지금까지의 설정을 저장한 뒤, 서버를 실행합니다.
  ```
  php artisan serve
  ```
10. 서버가 실행되면, 후쿠오카 레스토랑 크롤러의 백엔드 서비스를 사용할 수 있습니다. 이제 프론트엔드 레포지토리를 설정하고 React로 구축된 웹 애플리케이션을 실행할 수 있습니다. 백엔드 서비스의 기본 URL은 http://localhost:8000입니다.
