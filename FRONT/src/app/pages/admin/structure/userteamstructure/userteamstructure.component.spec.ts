import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { UserteamstructureComponent } from './userteamstructure.component';

describe('UserteamstructureComponent', () => {
  let component: UserteamstructureComponent;
  let fixture: ComponentFixture<UserteamstructureComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ UserteamstructureComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(UserteamstructureComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
