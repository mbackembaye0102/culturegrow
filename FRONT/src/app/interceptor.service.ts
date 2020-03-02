import { Injectable, Injector } from '@angular/core';
import { HttpInterceptor } from '@angular/common/http';
import {AuthService} from './service/auth.service';
@Injectable({
  providedIn: 'root'
})
export class InterceptorService implements HttpInterceptor {

  constructor(private injector : Injector) { }
  intercept(req,next){
    let authentification = this.injector.get(AuthService)

    let tokenizeReq = req.clone({
      setHeaders : {
        Authorization: `Bearer ${authentification.getToken()}`
      }
    })
    return next.handle(tokenizeReq)
  }
}
